<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyAssessment;
use App\Models\Competency;
use App\Models\User;
use App\Models\Training;
use Illuminate\Http\Request;

class GapAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status'); // critical, moderate, minimal, proficient
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyAssessment::with(['driver', 'competency'])
            ->orderBy('score', 'asc');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->whereHas('competency', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        if ($status) {
            if ($status === 'critical') {
                $query->where('score', '<', 60);
            } elseif ($status === 'moderate') {
                $query->whereBetween('score', [60, 74.99]);
            } elseif ($status === 'minimal') {
                $query->whereBetween('score', [75, 84.99]);
            } elseif ($status === 'proficient') {
                $query->where('score', '>=', 85);
            }
        }

        $assessments = $query->paginate($perPage)->withQueryString();

        // Calculate real-time stats across all driver assessments
        $totalAssessments = CompetencyAssessment::count();
        $criticalGaps = CompetencyAssessment::where('score', '<', 60)->count();
        $moderateGaps = CompetencyAssessment::whereBetween('score', [60, 74.99])->count();
        $proficientCount = CompetencyAssessment::where('score', '>=', 85)->count();
        
        $avgCurrentScore = CompetencyAssessment::avg('score') ?? 0;
        $avgTargetScore = 85.0; // Benchmark target
        $overallGapPercentage = max(0, number_format($avgTargetScore - $avgCurrentScore, 1));

        $stats = [
            'total_assessments' => $totalAssessments,
            'critical_gaps' => $criticalGaps,
            'moderate_gaps' => $moderateGaps,
            'proficient_count' => $proficientCount,
            'avg_current_score' => number_format($avgCurrentScore, 1),
            'avg_target_score' => number_format($avgTargetScore, 1),
            'overall_gap' => $overallGapPercentage,
        ];

        // Competency Category Gaps Summary for Chart & Summary Cards
        $categoryGaps = Competency::leftJoin('competency_assessments', 'competencies.id', '=', 'competency_assessments.competency_id')
            ->selectRaw('competencies.category, competencies.name as competency_name, AVG(competency_assessments.score) as avg_actual, competencies.target_score')
            ->groupBy('competencies.id', 'competencies.category', 'competencies.name', 'competencies.target_score')
            ->get();

        $recommendedTrainings = Training::latest()->take(5)->get();

        return view('admin.competency.gap-analysis', compact(
            'assessments',
            'stats',
            'categoryGaps',
            'recommendedTrainings'
        ));
    }
}
