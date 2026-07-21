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

        $stats = [
            'high_competency' => CompetencyAssessment::where('score', '>=', 85)->distinct('driver_id')->count(),
            'needs_improvement' => CompetencyAssessment::where('score', '<', 70)->distinct('driver_id')->count(),
            'avg_score' => number_format(CompetencyAssessment::avg('score'), 2),
            'skill_gaps' => CompetencyAssessment::where('score', '<', 70)->count(),
        ];

        return view('admin.competency.assessment-results', compact('results', 'stats'));
    }
}
