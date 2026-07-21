<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyAssessment;
use Illuminate\Http\Request;

class CompetencyAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $competencyId = $request->query('competency_id');
        $month = $request->query('month');
        $quarter = $request->query('quarter');
        $year = $request->query('year', date('Y'));

        $query = CompetencyAssessment::with(['driver', 'competency']);

        if ($competencyId) {
            $query->where('competency_id', $competencyId);
        }

        if ($year) {
            $query->whereYear('assessed_at', $year);
        }

        $assessments = $query->get();

        $stats = [
            'avg_score' => $assessments->avg('score') ? number_format($assessments->avg('score'), 2) : '0.00',
            'highest' => $assessments->max('score') ?: 'N/A',
            'lowest' => $assessments->min('score') ?: 'N/A',
            'growth_rate' => '+2.4%',
        ];

        $competencies = \App\Models\Competency::all();

        return view('admin.competency.competency-analytics', compact('assessments', 'stats', 'competencies'));
    }
}
