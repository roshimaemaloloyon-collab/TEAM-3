<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class PerformanceReviewsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
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
            if ($status === 'completed') {
                $query->where('status', 'active');
            } elseif ($status === 'pending') {
                $query->where('status', 'review');
            }
        }

        $drivers = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        $allCount = Driver::query()->notArchived()->count();
        $completedCount = Driver::query()->notArchived()->where('status', 'active')->count();

        $stats = [
            'completed' => $completedCount,
            'pending' => max(0, $allCount - $completedCount),
            'monthly' => intval($allCount * 0.7),
            'quarterly' => intval($allCount * 0.3),
        ];

        return view('admin.performance.performance-reviews', compact('drivers', 'stats'));
    }
}
