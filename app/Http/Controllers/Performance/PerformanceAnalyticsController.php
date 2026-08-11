<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\Kpi;
use App\Models\PerformanceReview;
use Carbon\Carbon;
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

        $avgOverall = $performances->avg('overall_score') ?? 0;
        $avgKpi = Kpi::avg('achievement_percentage') ?? 0;
        $avgSafety = $performances->avg('safety_score') ?? 0;
        $avgCustomer = $performances->avg('customer_rating') ?? 0;

        $stats = [
            'avg_score' => number_format($avgOverall, 2),
            'kpi_achievement' => number_format($avgKpi, 2) . '%',
            'safety_rating' => number_format($avgSafety, 2),
            'customer_satisfaction' => number_format($avgCustomer, 2),
        ];

        if (config('database.default') === 'pgsql') {
            $perfTrend = Performance::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, AVG(overall_score) as avg_score")
                ->whereNotNull('recorded_at')
                ->when($year, fn($q) => $q->whereYear('recorded_at', $year))
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get()
                ->map(fn($item) => [
                    'month' => Carbon::createFromFormat('m', $item->month_num)->format('M'),
                    'avg_score' => $item->avg_score,
                ]);
        } else {
            $perfTrend = Performance::selectRaw('strftime("%m", recorded_at) as month_num, AVG(overall_score) as avg_score')
                ->whereNotNull('recorded_at')
                ->when($year, fn($q) => $q->whereYear('recorded_at', $year))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get()
                ->map(fn($item) => [
                    'month' => Carbon::createFromFormat('m', $item->month_num)->format('M'),
                    'avg_score' => $item->avg_score,
                ]);
        }

        $kpiByCategory = Kpi::selectRaw('kpi_category, AVG(achievement_percentage) as avg_achievement')
            ->groupBy('kpi_category')
            ->get();

        $safetyDistribution = [
            'Excellent' => $performances->where('safety_score', '>=', 4.5)->count(),
            'Good' => $performances->whereBetween('safety_score', [3.5, 4.49])->count(),
            'Average' => $performances->whereBetween('safety_score', [2.5, 3.49])->count(),
            'Poor' => $performances->where('safety_score', '<', 2.5)->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $attendanceTrend = Performance::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, AVG(attendance_rate) as avg_attendance")
                ->whereNotNull('recorded_at')
                ->when($year, fn($q) => $q->whereYear('recorded_at', $year))
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get()
                ->map(fn($item) => [
                    'month' => Carbon::createFromFormat('m', $item->month_num)->format('M'),
                    'avg_attendance' => $item->avg_attendance,
                ]);
        } else {
            $attendanceTrend = Performance::selectRaw('strftime("%m", recorded_at) as month_num, AVG(attendance_rate) as avg_attendance')
                ->whereNotNull('recorded_at')
                ->when($year, fn($q) => $q->whereYear('recorded_at', $year))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get()
                ->map(fn($item) => [
                    'month' => Carbon::createFromFormat('m', $item->month_num)->format('M'),
                    'avg_attendance' => $item->avg_attendance,
                ]);
        }

        $customerByDriver = $performances->groupBy('driver_id')->map(fn($items) => $items->avg('customer_rating') ?? 0);
        $peerByDriver = $performances->groupBy('driver_id')->map(fn($items) => $items->avg('peer_evaluation_score') ?? 0);

        $driverLabels = $customerByDriver->keys()->map(fn($id) => $performances->firstWhere('driver_id', $id)?->driver?->name ?? 'Driver')->toArray();

        $comparative = $performances->groupBy('driver_id')->map(function ($items) {
            return [
                'performance' => $items->avg('overall_score') ?? 0,
                'kpi' => $items->avg('attendance_rate') ?? 0,
            ];
        });

        return view('admin.performance.performance-analytics', compact(
            'performances',
            'stats',
            'perfTrend',
            'kpiByCategory',
            'safetyDistribution',
            'attendanceTrend',
            'customerByDriver',
            'peerByDriver',
            'driverLabels',
            'comparative'
        ));
    }
}
