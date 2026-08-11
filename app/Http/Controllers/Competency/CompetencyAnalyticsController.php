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

        $avgScore = $assessments->avg('score') ?? 0;
        $highest = $assessments->max('score') ?? 0;
        $lowest = $assessments->min('score') ?? 0;

        $stats = [
            'avg_score' => number_format($avgScore, 2),
            'highest' => number_format($highest, 2),
            'lowest' => number_format($lowest, 2),
            'growth_rate' => '+2.4%',
        ];

        $competencies = \App\Models\Competency::all();

        $compDist = CompetencyAssessment::selectRaw('competency_id, AVG(score) as avg_score')
            ->groupBy('competency_id')
            ->get();

        $skillDist = CompetencyAssessment::selectRaw('competency_id, COUNT(*) as total')
            ->groupBy('competency_id')
            ->get();

        if (config('database.default') === 'pgsql') {
            $trend = CompetencyAssessment::selectRaw("TO_CHAR(assessed_at, 'MM') as month_num, AVG(score) as avg_score")
                ->whereNotNull('assessed_at')
                ->when($year, fn($q) => $q->whereYear('assessed_at', $year))
                ->groupByRaw("TO_CHAR(assessed_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();

            $growth = CompetencyAssessment::selectRaw("TO_CHAR(assessed_at, 'MM') as month_num, AVG(score) as avg_score")
                ->whereNotNull('assessed_at')
                ->when($year, fn($q) => $q->whereYear('assessed_at', $year))
                ->groupByRaw("TO_CHAR(assessed_at, 'MM')")
                ->orderBy('month_num')
                ->get();
        } else {
            $trend = CompetencyAssessment::selectRaw('strftime("%m", assessed_at) as month_num, AVG(score) as avg_score')
                ->whereNotNull('assessed_at')
                ->when($year, fn($q) => $q->whereYear('assessed_at', $year))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();

            $growth = CompetencyAssessment::selectRaw('strftime("%m", assessed_at) as month_num, AVG(score) as avg_score')
                ->whereNotNull('assessed_at')
                ->when($year, fn($q) => $q->whereYear('assessed_at', $year))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->get();
        }

        $comparative = $assessments->groupBy('driver_id')->map(fn($items) => $items->avg('score') ?? 0);

        return view('admin.competency.competency-analytics', compact(
            'assessments',
            'stats',
            'competencies',
            'compDist',
            'skillDist',
            'trend',
            'growth',
            'comparative'
        ));
    }
}
