<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Kpi;
use Illuminate\Http\Request;

class KpiMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Kpi::with('driver')->orderByDesc('achievement_percentage');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('kpi_name', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('kpi_category', $category);
        }

        $kpis = $query->paginate($perPage)->withQueryString();

        $stats = [
            'avg_kpi' => Kpi::avg('achievement_percentage') ? number_format(Kpi::avg('achievement_percentage'), 2) . '%' : '0%',
            'target_achievement' => Kpi::where('status', 'achieved')->count() . ' / ' . Kpi::count(),
            'meeting_kpi' => Kpi::where('status', 'achieved')->count(),
            'below_target' => Kpi::where('status', 'missed')->count(),
        ];

        return view('admin.performance.kpi-monitoring', compact('kpis', 'stats'));
    }
}
