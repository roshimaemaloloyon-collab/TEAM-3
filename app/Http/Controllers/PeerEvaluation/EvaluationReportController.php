<?php

namespace App\Http\Controllers\PeerEvaluation;

use App\Http\Controllers\Controller;
use App\Models\EvaluationReport;
use App\Models\PeerEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = EvaluationReport::query()
            ->orderByDesc('generated_at');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('report_type', $type);
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'reports_generated' => EvaluationReport::count(),
            'monthly_reports' => EvaluationReport::where('report_type', 'monthly')->count(),
            'avg_evaluation_score' => PeerEvaluation::whereNotNull('overall_score')->avg('overall_score'),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.peer-evaluation.evaluation-reports', compact('reports', 'stats', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|string|in:individual,department,monthly,quarterly',
            'title' => 'required|string|max:255',
            'parameters' => 'nullable|array',
            'export_format' => 'nullable|string|in:pdf,excel,print',
        ]);

        $validated['generated_by'] = auth()->id() ?? 1;
        $validated['generated_at'] = now();

        EvaluationReport::create($validated);

        return back()->with('success', 'Report generated successfully.');
    }
}
