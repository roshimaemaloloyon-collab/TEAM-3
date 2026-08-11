<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningHistory;
use Illuminate\Http\Request;

class LearningHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = LearningHistory::with(['driver', 'module'])->orderByDesc('recorded_at');

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
            'historical_records' => LearningHistory::count(),
            'completed_courses' => LearningHistory::where('record_type', 'completion')->count(),
            'certificates_earned' => LearningHistory::where('record_type', 'certificate')->count(),
            'assessments_taken' => LearningHistory::where('record_type', 'assessment')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $timelineData = LearningHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $timelineData = LearningHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $trendData = LearningHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $trendData = LearningHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        return view('admin.learning.learning-history', compact('histories', 'stats', 'timelineData', 'trendData'));
    }
}
