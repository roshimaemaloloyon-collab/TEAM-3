<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\User;
use Illuminate\Http\Request;

class DriverPerformanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Performance::with('driver')->orderByDesc('overall_score');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('performance_status', $status);
        }

        $performances = $query->paginate($perPage)->withQueryString();

        $stats = [
            'avg_score' => Performance::avg('overall_score') ? number_format(Performance::avg('overall_score'), 2) : '0.00',
            'top_drivers' => Performance::where('performance_status', 'excellent')->count(),
            'needs_improvement' => Performance::where('performance_status', 'needs_improvement')->count(),
            'avg_rating' => number_format(Performance::avg('customer_rating'), 2),
        ];

        return view('admin.performance.driver-performance', compact('performances', 'stats'));
    }
}
