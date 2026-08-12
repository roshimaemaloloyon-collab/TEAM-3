<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class PerformanceReportsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
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

        $drivers = $query->orderByDesc('performance_score')->paginate($perPage)->withQueryString();

        $allCount = Driver::query()->notArchived()->count();

        $stats = [
            'generated' => $allCount,
            'individual' => intval($allCount * 0.6),
            'ranking' => intval($allCount * 0.25),
            'kpi' => intval($allCount * 0.15),
        ];

        return view('admin.performance.performance-reports', compact('drivers', 'stats'));
    }
}
