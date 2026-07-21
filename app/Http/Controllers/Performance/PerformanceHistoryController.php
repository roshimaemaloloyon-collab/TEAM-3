<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\PerformanceHistory;
use Illuminate\Http\Request;

class PerformanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = PerformanceHistory::with('driver')->orderByDesc('recorded_at');

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
            'historical_records' => PerformanceHistory::count(),
            'archived_reviews' => PerformanceHistory::where('record_type', 'review')->count(),
            'timeline_events' => PerformanceHistory::count(),
            'ranking_changes' => PerformanceHistory::where('record_type', 'ranking_change')->count(),
        ];

        return view('admin.performance.performance-history', compact('histories', 'stats'));
    }
}
