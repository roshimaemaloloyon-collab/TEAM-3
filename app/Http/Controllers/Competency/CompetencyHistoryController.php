<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyHistory;
use Illuminate\Http\Request;

class CompetencyHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyHistory::with(['driver', 'competency'])->orderByDesc('recorded_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($recordType) {
            $query->where('record_type', $recordType);
        }

        $histories = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_records' => CompetencyHistory::count(),
            'assessments' => CompetencyHistory::where('record_type', 'assessment')->count(),
            'coaching_sessions' => CompetencyHistory::where('record_type', 'coaching')->count(),
            'reviews' => CompetencyHistory::where('record_type', 'review')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $timelineData = CompetencyHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $timelineData = CompetencyHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $trendData = CompetencyHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, AVG(score) as avg_score")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $trendData = CompetencyHistory::selectRaw('strftime("%m", recorded_at) as month_num, AVG(score) as avg_score')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }

        return view('admin.competency.competency-history', compact('histories', 'stats', 'timelineData', 'trendData'));
    }
}
