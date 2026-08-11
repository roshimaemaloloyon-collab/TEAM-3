<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class LearningReportsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Report::where('category', 'learning')->orderByDesc('generated_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('report_type', $type);
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'generated' => Report::where('category', 'learning')->count(),
            'completion' => Report::where('category', 'learning')->where('report_type', 'completion')->count(),
            'driver_reports' => Report::where('category', 'learning')->where('report_type', 'driver')->count(),
            'module_reports' => Report::where('category', 'learning')->where('report_type', 'module')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $completionTrend = Report::selectRaw("TO_CHAR(generated_at, 'MM') as month_num, COUNT(*) as total")
                ->where('category', 'learning')
                ->whereNotNull('generated_at')
                ->groupByRaw("TO_CHAR(generated_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $completionTrend = Report::selectRaw('strftime("%m", generated_at) as month_num, COUNT(*) as total')
                ->where('category', 'learning')
                ->whereNotNull('generated_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }

        $reportAnalytics = Report::selectRaw('report_type, COUNT(*) as total')
            ->where('category', 'learning')
            ->groupBy('report_type')
            ->get();

        return view('admin.learning.learning-reports', compact('reports', 'stats', 'completionTrend', 'reportAnalytics'));
    }
}
