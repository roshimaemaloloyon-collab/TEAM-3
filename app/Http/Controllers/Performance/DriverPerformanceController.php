<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverPerformanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
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

        if ($status) {
            if ($status === 'excellent') {
                $query->where('performance_score', '>=', 4.8);
            } elseif ($status === 'good') {
                $query->whereBetween('performance_score', [4.5, 4.79]);
            } elseif ($status === 'average') {
                $query->whereBetween('performance_score', [4.0, 4.49]);
            } elseif ($status === 'needs_improvement') {
                $query->where('performance_score', '<', 4.0);
            }
        }

        $drivers = $query->orderByDesc('performance_score')->paginate($perPage)->withQueryString();

        $allDrivers = Driver::query()->notArchived();
        $stats = [
            'avg_score' => $allDrivers->avg('performance_score') ? number_format($allDrivers->avg('performance_score'), 2) : '4.50',
            'top_drivers' => Driver::query()->notArchived()->where('performance_score', '>=', 4.8)->count(),
            'needs_improvement' => Driver::query()->notArchived()->where('performance_score', '<', 4.0)->count(),
            'avg_rating' => '4.85',
        ];

        return view('admin.performance.driver-performance', compact('drivers', 'stats'));
    }
}
