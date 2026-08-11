<?php

namespace App\Http\Controllers\PeerEvaluation;

use App\Http\Controllers\Controller;
use App\Models\PeerEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', 'monthly');

        if (config('database.default') === 'pgsql') {
            $monthlyTrend = PeerEvaluation::selectRaw("TO_CHAR(evaluation_date, 'MM') as month_num, AVG(overall_score) as avg_score")
                ->groupByRaw("TO_CHAR(evaluation_date, 'MM')")
                ->orderBy('month_num')
                ->limit(12)
                ->get();
        } else {
            $monthlyTrend = PeerEvaluation::selectRaw('strftime("%m", evaluation_date) as month_num, AVG(overall_score) as avg_score')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(12)
                ->get();
        }

        if (config('database.default') === 'pgsql') {
            $categoryPerformance = PeerEvaluation::selectRaw('category_scores as scores')
                ->whereNotNull('category_scores')
                ->get();
        } else {
            $categoryPerformance = PeerEvaluation::selectRaw('JSON_EXTRACT(category_scores, "$.*") as scores')
                ->whereNotNull('category_scores')
                ->get();
        }

        $driverRanking = PeerEvaluation::select('evaluated_driver_id')
            ->selectRaw('COUNT(*) as evaluation_count')
            ->selectRaw('AVG(overall_score) as avg_score')
            ->groupBy('evaluated_driver_id')
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get();

        $stats = [
            'avg_peer_score' => PeerEvaluation::whereNotNull('overall_score')->avg('overall_score'),
            'highest_rated_category' => 'Professionalism',
            'monthly_evaluations' => PeerEvaluation::whereMonth('evaluation_date', now()->month)->count(),
            'completion_rate' => PeerEvaluation::whereIn('status', ['approved', 'submitted'])->count() / max(PeerEvaluation::count(), 1) * 100,
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.peer-evaluation.evaluation-analytics', compact('monthlyTrend', 'categoryPerformance', 'driverRanking', 'stats', 'drivers'));
    }
}
