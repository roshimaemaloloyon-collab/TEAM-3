<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningHistory;
use Illuminate\Http\Request;

class LearningHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = LearningHistory::with(['driver', 'module'])->orderByDesc('recorded_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($recordType) {
            $query->where('record_type', $recordType);
        }

        $histories = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_records' => LearningHistory::count(),
            'completed_courses' => LearningHistory::where('record_type', 'completion')->count(),
            'certificates_earned' => LearningHistory::where('record_type', 'certificate')->count(),
            'assessments_taken' => LearningHistory::where('record_type', 'assessment')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $timelineData = LearningHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $timelineData = LearningHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $trendData = LearningHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $trendData = LearningHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        return view('admin.learning.learning-history', compact('histories', 'stats', 'timelineData', 'trendData'));
    }

    public function export(Request $request)
    {
        $id = $request->query('id');
        $history = LearningHistory::with(['driver', 'module'])->find($id);

        $driverName = $history->driver->name ?? 'Driver';
        $moduleTitle = $history->module->title ?? 'Module';
        $score = $history->score ?? 'N/A';
        $recordedAt = $history->recorded_at ? $history->recorded_at->format('M d, Y') : 'N/A';

        $filename = "learning_history_record_" . ($history ? $history->id : 'log') . ".pdf";

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<title>Learning History Official Report</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 30px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #ef4444; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #991b1b; margin: 0; font-size: 20px; text-transform: uppercase; }';
        $html .= '.box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px; }';
        $html .= '.field { margin-bottom: 8px; font-size: 13px; }';
        $html .= '.label { font-weight: bold; color: #64748b; text-transform: uppercase; font-size: 11px; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — LEARNING HISTORY REPORT</h1><p>Driver Training & Certification Record Verification</p></div>';
        $html .= '<div class="box">';
        $html .= '<div class="field"><span class="label">Driver Name:</span> <strong>' . htmlspecialchars($driverName) . '</strong></div>';
        $html .= '<div class="field"><span class="label">Learning Module:</span> ' . htmlspecialchars($moduleTitle) . '</div>';
        $html .= '<div class="field"><span class="label">Completion Date:</span> ' . htmlspecialchars($recordedAt) . '</div>';
        $html .= '<div class="field"><span class="label">Quiz Score:</span> ' . htmlspecialchars($score) . '</div>';
        $html .= '</div>';
        $html .= '<script>window.onload = function() { window.print(); };</script>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function restore($id)
    {
        $history = LearningHistory::findOrFail($id);
        $history->record_type = 'completion';
        $history->save();

        return back()->with('success', 'Learning record restored to Completion status.');
    }

    public function destroy($id)
    {
        $history = LearningHistory::findOrFail($id);
        $history->delete();

        return back()->with('success', 'Learning history record archived/deleted successfully.');
    }
}
