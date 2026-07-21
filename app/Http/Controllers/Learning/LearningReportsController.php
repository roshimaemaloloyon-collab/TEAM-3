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

        return view('admin.learning.learning-reports', compact('reports', 'stats'));
    }
}
