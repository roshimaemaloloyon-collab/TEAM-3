<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class KpiMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
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

        if ($category) {
            if ($category === 'safety') {
                $query->where('performance_score', '>=', 4.8);
            } elseif ($category === 'attendance') {
                $query->where('trips_count', '>=', 100);
            } elseif ($category === 'customer_service') {
                $query->where('complaints_count', 0);
            }
        }

        $drivers = $query->orderByDesc('performance_score')->paginate($perPage)->withQueryString();

        $allCount = Driver::query()->notArchived()->count();
        $achievedCount = Driver::query()->notArchived()->where('performance_score', '>=', 4.5)->count();

        $stats = [
            'avg_kpi' => '94.2%',
            'target_achievement' => "{$achievedCount} / {$allCount}",
            'meeting_kpi' => $achievedCount,
            'below_target' => max(0, $allCount - $achievedCount),
        ];

        return view('admin.performance.kpi-monitoring', compact('drivers', 'stats'));
    }
}
