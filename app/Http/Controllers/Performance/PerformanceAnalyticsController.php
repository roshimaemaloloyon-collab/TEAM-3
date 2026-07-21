<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Kpi;
use App\Models\PerformanceReview;
use Illuminate\Http\Request;

class PerformanceAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $driver = $request->query('driver');
        $month = $request->query('month');
        $quarter = $request->query('quarter');
        $year = $request->query('year', date('Y'));

        $query = Performance::with('driver');

        if ($driver) {
            $query->where('driver_id', $driver);
        }

        if ($year) {
            $query->whereYear('recorded_at', $year);
        }

        $performances = $query->get();

        $stats = [
            'avg_score' => $performances->avg('overall_score') ? number_format($performances->avg('overall_score'), 2) : '0.00',
            'kpi_achievement' => Kpi::avg('achievement_percentage') ? number_format(Kpi::avg('achievement_percentage'), 2) . '%' : '0%',
            'safety_rating' => $performances->avg('safety_score') ? number_format($performances->avg('safety_score'), 2) : '0.00',
            'customer_satisfaction' => $performances->avg('customer_rating') ? number_format($performances->avg('customer_rating'), 2) : '0.00',
        ];

        return view('admin.performance.performance-analytics', compact('performances', 'stats'));
    }
}
