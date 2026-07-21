<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class CompetencyReportsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Report::where('category', 'competency')->orderByDesc('generated_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('report_type', $type);
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'generated' => Report::where('category', 'competency')->count(),
            'competency' => Report::where('category', 'competency')->where('report_type', 'competency')->count(),
            'skill' => Report::where('category', 'competency')->where('report_type', 'skill')->count(),
            'analytics' => Report::where('category', 'competency')->where('report_type', 'analytics')->count(),
        ];

        return view('admin.competency.competency-reports', compact('reports', 'stats'));
    }
}
