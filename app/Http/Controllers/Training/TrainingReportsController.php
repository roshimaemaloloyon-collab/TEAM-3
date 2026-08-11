<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TrainingReportsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Report::where('category', 'training')->orderByDesc('generated_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('report_type', $type);
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'generated' => Report::where('category', 'training')->count(),
            'attendance' => Report::where('category', 'training')->where('report_type', 'attendance')->count(),
            'completion' => Report::where('category', 'training')->where('report_type', 'completion')->count(),
            'training' => Report::where('category', 'training')->where('report_type', 'training')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $monthlyReports = Report::selectRaw("TO_CHAR(generated_at, 'MM') as month_num, COUNT(*) as total")->where('category', 'training')
            ->whereNotNull('generated_at')
            ->groupByRaw("TO_CHAR(generated_at, 'MM')")
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $monthlyReports = Report::selectRaw('strftime("%m", generated_at) as month_num, COUNT(*) as total')->where('category', 'training')
            ->whereNotNull('generated_at')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $reportTrend = Report::selectRaw("TO_CHAR(generated_at, 'MM') as month_num, COUNT(*) as total")->where('category', 'training')
            ->whereNotNull('generated_at')
            ->groupByRaw("TO_CHAR(generated_at, 'MM')")
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $reportTrend = Report::selectRaw('strftime("%m", generated_at) as month_num, COUNT(*) as total')->where('category', 'training')
            ->whereNotNull('generated_at')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        return view('admin.training.reports', compact('reports', 'stats', 'monthlyReports', 'reportTrend'));
    }
}
