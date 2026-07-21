<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class PerformanceReportsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Report::where('category', 'performance')->orderByDesc('generated_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('report_type', $type);
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'generated' => Report::where('category', 'performance')->count(),
            'individual' => Report::where('category', 'performance')->where('report_type', 'individual')->count(),
            'ranking' => Report::where('category', 'performance')->where('report_type', 'ranking')->count(),
            'kpi' => Report::where('category', 'performance')->where('report_type', 'kpi')->count(),
        ];

        return view('admin.performance.performance-reports', compact('reports', 'stats'));
    }
}
