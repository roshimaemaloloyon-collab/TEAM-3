<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Training::where('status', 'completed')->orderByDesc('start_datetime');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $history = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_trainings' => Training::where('status', 'completed')->count(),
            'archived_sessions' => Training::where('status', 'completed')->count(),
            'timeline_events' => Training::count(),
            'completed_programs' => Training::where('status', 'completed')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $timelineData = Training::selectRaw("TO_CHAR(start_datetime, 'MM') as month_num, COUNT(*) as total")->where('status', 'completed')
            ->whereNotNull('start_datetime')
            ->groupByRaw("TO_CHAR(start_datetime, 'MM')")
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $timelineData = Training::selectRaw('strftime("%m", start_datetime) as month_num, COUNT(*) as total')->where('status', 'completed')
            ->whereNotNull('start_datetime')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $trendData = Training::selectRaw("TO_CHAR(start_datetime, 'MM') as month_num, COUNT(*) as total")->where('status', 'completed')
            ->whereNotNull('start_datetime')
            ->groupByRaw("TO_CHAR(start_datetime, 'MM')")
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $trendData = Training::selectRaw('strftime("%m", start_datetime) as month_num, COUNT(*) as total')->where('status', 'completed')
            ->whereNotNull('start_datetime')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        return view('admin.training.history', compact('history', 'stats', 'timelineData', 'trendData'));
    }
}
