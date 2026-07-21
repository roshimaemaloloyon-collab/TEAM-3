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

        return view('admin.training.reports', compact('reports', 'stats'));
    }
}
