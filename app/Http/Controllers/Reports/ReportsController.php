<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsData;
use App\Models\EvaluationReport;
use App\Models\Report;
use App\Models\ReportExport;
use App\Models\ReportHistory;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function driverReports(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Report::query()
            ->where('category', 'driver')
            ->orderByDesc('generated_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('report_type', $type);
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => Report::where('category', 'driver')->count(),
            'performance' => Report::where('category', 'driver')->where('report_type', 'performance')->count(),
            'training' => Report::where('category', 'driver')->where('report_type', 'training')->count(),
            'learning' => Report::where('category', 'driver')->where('report_type', 'learning')->count(),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.reports.driver-reports', compact('reports', 'stats', 'drivers'));
    }

    public function evaluationReports(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Report::query()
            ->whereIn('category', ['evaluation', 'peer_evaluation', 'recognition', 'succession'])
            ->orderByDesc('generated_at');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $reports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'evaluation' => Report::where('category', 'evaluation')->count(),
            'recognition' => Report::where('category', 'recognition')->count(),
            'promotion' => Report::where('category', 'succession')->count(),
            'feedback' => Report::where('category', 'peer_evaluation')->count(),
        ];

        return view('admin.reports.evaluation-reports', compact('reports', 'stats'));
    }

    public function analyticsDashboard(Request $request)
    {
        $period = $request->query('period', 'monthly');

        $performanceData = AnalyticsData::where('category', 'performance')
            ->orderByDesc('recorded_date')
            ->limit(12)
            ->get();

        $competencyData = AnalyticsData::where('category', 'competency')
            ->orderByDesc('recorded_date')
            ->limit(12)
            ->get();

        $trainingData = AnalyticsData::where('category', 'training')
            ->orderByDesc('recorded_date')
            ->limit(12)
            ->get();

        $learningData = AnalyticsData::where('category', 'learning')
            ->orderByDesc('recorded_date')
            ->limit(12)
            ->get();

        $recognitionData = AnalyticsData::where('category', 'recognition')
            ->orderByDesc('recorded_date')
            ->limit(12)
            ->get();

        $evaluationData = AnalyticsData::where('category', 'evaluation')
            ->orderByDesc('recorded_date')
            ->limit(12)
            ->get();

        $stats = [
            'avg_performance' => 4.5,
            'competency_completion' => 78,
            'learning_completion' => 82,
            'training_completion' => 85,
            'peer_evaluation_score' => 4.3,
            'recognition_count' => 156,
        ];

        return view('admin.reports.analytics-dashboard', compact(
            'performanceData', 'competencyData', 'trainingData',
            'learningData', 'recognitionData', 'evaluationData', 'stats'
        ));
    }

    public function dataVisualization(Request $request)
    {
        $chartType = $request->query('chart_type', 'bar');
        $period = $request->query('period', 'monthly');
        $driverId = $request->query('driver_id');

        $query = AnalyticsData::query();

        if ($driverId) {
            $query->where('recorded_by', $driverId);
        }

        $analyticsData = $query->orderByDesc('recorded_date')->get();

        $stats = [
            'total_datasets' => AnalyticsData::count(),
            'categories' => AnalyticsData::select('category')->distinct()->count(),
            'date_range' => AnalyticsData::select('recorded_date')->orderBy('recorded_date')->first()?->format('M Y') . ' - ' . AnalyticsData::select('recorded_date')->orderByDesc('recorded_date')->first()?->format('M Y'),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.reports.data-visualization', compact('analyticsData', 'stats', 'chartType', 'period', 'drivers'));
    }

    public function exportCenter(Request $request)
    {
        $search = $request->query('search');
        $format = $request->query('format');
        $perPage = (int) ($request->query('per_page', 15));

        $query = ReportExport::with(['report', 'exportedBy'])
            ->orderByDesc('exported_at');

        if ($search) {
            $query->whereHas('report', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($format) {
            $query->where('export_format', $format);
        }

        $exports = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_exports' => ReportExport::count(),
            'pdf_exports' => ReportExport::where('export_format', 'pdf')->count(),
            'excel_exports' => ReportExport::where('export_format', 'excel')->count(),
            'printed_reports' => ReportExport::where('export_format', 'print')->count(),
        ];

        return view('admin.reports.export-center', compact('exports', 'stats'));
    }

    public function reportHistory(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) ($request->query('per_page', 15));

        $query = ReportHistory::with(['report', 'performedBy'])
            ->orderByDesc('performed_at');

        if ($search) {
            $query->whereHas('report', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $history = $query->paginate($perPage)->withQueryString();

        $stats = [
            'reports_generated' => Report::count(),
            'downloads' => ReportHistory::where('action', 'downloaded')->count(),
            'scheduled_reports' => Report::where('status', 'scheduled')->count(),
            'archived_reports' => Report::where('status', 'archived')->count(),
        ];

        return view('admin.reports.report-history', compact('history', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:driver,evaluation,analytics,visualization,export,history',
            'report_type' => 'required|string|max:100',
            'parameters' => 'nullable|array',
            'export_format' => 'nullable|string|in:pdf,excel,print',
        ]);

        $validated['generated_by'] = auth()->id() ?? 1;
        $validated['generated_at'] = now();
        $validated['status'] = 'generated';

        $report = Report::create($validated);

        ReportHistory::create([
            'report_id' => $report->id,
            'action' => 'generated',
            'performed_by' => auth()->id() ?? 1,
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Report generated successfully.');
    }

    public function export(Request $request, Report $report)
    {
        $validated = $request->validate([
            'export_format' => 'required|string|in:pdf,excel,print',
        ]);

        $export = ReportExport::create([
            'report_id' => $report->id,
            'export_format' => $validated['export_format'],
            'exported_by' => auth()->id() ?? 1,
            'exported_at' => now(),
        ]);

        ReportHistory::create([
            'report_id' => $report->id,
            'action' => 'exported',
            'performed_by' => auth()->id() ?? 1,
            'performed_at' => now(),
            'notes' => "Exported as {$validated['export_format']}",
        ]);

        return back()->with('success', 'Report exported successfully.');
    }
}
