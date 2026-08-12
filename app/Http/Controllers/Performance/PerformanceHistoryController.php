<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class PerformanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Driver::query()->notArchived();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('driver_id', 'like', "%{$search}%")
                  ->orWhere('vehicle_assignment', 'like', "%{$search}%");
            });
        }

        $drivers = $query->orderByDesc('updated_at')->paginate($perPage)->withQueryString();

        $allCount = Driver::query()->notArchived()->count();

        $stats = [
            'historical_records' => $allCount * 4,
            'archived_reviews' => $allCount * 2,
            'timeline_events' => $allCount * 5,
            'ranking_changes' => intval($allCount * 0.8),
        ];

        return view('admin.performance.performance-history', compact('drivers', 'stats'));
    }
}
