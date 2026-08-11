<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyAssessment;
use Illuminate\Http\Request;

class AssessmentResultsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyAssessment::with(['driver', 'competency'])->orderByDesc('assessed_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $results = $query->paginate($perPage)->withQueryString();

        $highCompetency = CompetencyAssessment::where('score', '>=', 85)->count();
        $needsImprovement = CompetencyAssessment::where('score', '<', 70)->count();
        $avgScore = CompetencyAssessment::avg('score') ?? 0;
        $skillGaps = CompetencyAssessment::where('score', '<', 70)->count();

        $stats = [
            'high_competency' => $highCompetency,
            'needs_improvement' => $needsImprovement,
            'avg_score' => number_format($avgScore, 2),
            'skill_gaps' => $skillGaps,
        ];

        $skillGapData = CompetencyAssessment::selectRaw('competency_id, AVG(score) as avg_score')
            ->groupBy('competency_id')
            ->get();

        if (config('database.default') === 'pgsql') {
            $trendData = CompetencyAssessment::selectRaw("TO_CHAR(assessed_at, 'MM') as month_num, AVG(overall_score) as avg_score")
                ->whereNotNull('assessed_at')
                ->groupByRaw("TO_CHAR(assessed_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $trendData = CompetencyAssessment::selectRaw('strftime("%m", assessed_at) as month_num, AVG(overall_score) as avg_score')
                ->whereNotNull('assessed_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        return view('admin.competency.assessment-results', compact('results', 'stats', 'skillGapData', 'trendData'));
    }
}
