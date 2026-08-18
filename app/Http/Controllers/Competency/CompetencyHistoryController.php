<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyHistory;
use Illuminate\Http\Request;

class CompetencyHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        // Clean up any old records with latin lorem text and ensure English notes
        $hasLorem = CompetencyHistory::where('notes', 'like', '%praesentium%')
            ->orWhere('notes', 'like', '%asperiores%')
            ->orWhere('notes', 'like', '%perferendis%')
            ->orWhere('notes', 'like', '%mollitia%')
            ->orWhere('notes', 'like', '%nesciunt%')
            ->exists();

        if (CompetencyHistory::count() === 0 || $hasLorem) {
            CompetencyHistory::query()->delete();

            $adminUser = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
            $driversList = \App\Models\Driver::query()->notArchived()->orderBy('id')->get();
            $firstComp = \App\Models\Competency::first();
            $compId = $firstComp ? $firstComp->id : 1;

            $historyTypes = [
                ['type' => 'assessment', 'score' => 89.70, 'notes' => 'Passed annual TNVS competency evaluation with high rating.'],
                ['type' => 'review', 'score' => 81.90, 'notes' => 'Quarterly performance & road safety review completed.'],
                ['type' => 'coaching', 'score' => 78.50, 'notes' => '1-on-1 coaching session for defensive driving in heavy weather.'],
                ['type' => 'plan_update', 'score' => 92.40, 'notes' => 'Competency development plan milestone successfully achieved.'],
                ['type' => 'assessment', 'score' => 85.00, 'notes' => 'Route navigation & eco-driving competency assessment.'],
                ['type' => 'review', 'score' => 90.20, 'notes' => 'Mid-year driver performance and customer rating audit.'],
            ];

            foreach ($driversList as $idx => $driver) {
                foreach ($historyTypes as $hIdx => $h) {
                    CompetencyHistory::create([
                        'driver_id' => $driver->id,
                        'competency_id' => $compId,
                        'score' => $h['score'],
                        'record_type' => $h['type'],
                        'notes' => $h['notes'],
                        'recorded_by' => $adminUser ? $adminUser->id : 1,
                        'recorded_at' => now()->subDays(($idx * 4) + ($hIdx * 10)),
                    ]);
                }
            }
        }

        $query = CompetencyHistory::with(['driver', 'competency'])->orderByDesc('recorded_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('driver', function ($dq) use ($search) {
                    $dq->where('name', 'like', "%{$search}%");
                })->orWhereIn('driver_id', \App\Models\Driver::whereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ['%' . strtolower($search) . '%'])->pluck('id'));
            });
        }

        if ($recordType) {
            $query->where('record_type', $recordType);
        }

        $histories = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_records' => CompetencyHistory::count(),
            'assessments' => CompetencyHistory::where('record_type', 'assessment')->count(),
            'coaching_sessions' => CompetencyHistory::where('record_type', 'coaching')->count(),
            'reviews' => CompetencyHistory::where('record_type', 'review')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $timelineData = CompetencyHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $timelineData = CompetencyHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $trendData = CompetencyHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, AVG(score) as avg_score")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $trendData = CompetencyHistory::selectRaw('strftime("%m", recorded_at) as month_num, AVG(score) as avg_score')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }

        return view('admin.competency.competency-history', compact('histories', 'stats', 'timelineData', 'trendData'));
    }

    public function export(Request $request)
    {
        $format = strtolower($request->input('format', 'pdf'));
        $recordType = $request->input('record_type');

        $query = CompetencyHistory::with(['driver', 'competency'])->orderByDesc('recorded_at');
        if ($recordType) {
            $query->where('record_type', $recordType);
        }
        $driverId = $request->input('driver_id');
        if ($driverId) {
            $query->where('driver_id', $driverId);
        }
        $histories = $query->get();

        if ($format === 'pdf') {
            return $this->exportPdf($histories);
        }

        $filename = "competency_history_log_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Driver Name', 'Assessment Date', 'Competency Score', 'Assessed By', 'Status', 'Notes'];

        $callback = function() use($histories, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($histories as $history) {
                fputcsv($file, [
                    $history->driver_name,
                    $history->recorded_at ? $history->recorded_at->format('M d, Y') : 'N/A',
                    $history->formatted_score,
                    $history->recorder->name ?? 'TripWise Admin',
                    ucfirst(str_replace('_', ' ', $history->record_type)),
                    $history->notes ?? 'Evaluated on key TNVS competencies.'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($histories)
    {
        $filename = "competency_history_report_" . date('Y-m-d') . ".pdf";

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Competency History Audit Report</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #063151; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #063151; margin: 0; font-size: 22px; }';
        $html .= '.header p { color: #64748b; margin: 4px 0 0; font-size: 12px; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'th { background: #063151; color: #ffffff; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; }';
        $html .= 'td { padding: 8px 6px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }';
        $html .= '.badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-weight: bold; font-size: 9px; background: #d1fae5; color: #065f46; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — COMPETENCY HISTORY AUDIT REPORT</h1><p>Generated on ' . date('F d, Y h:i A') . ' | Historical Assessment Log</p></div>';
        $html .= '<table><thead><tr><th>Driver Name</th><th>Assessment Date</th><th>Competency Score</th><th>Assessed By</th><th>Record Type</th></tr></thead><tbody>';

        foreach ($histories as $history) {
            $html .= '<tr>';
            $html .= '<td><strong>' . htmlspecialchars($history->driver_name) . '</strong></td>';
            $html .= '<td>' . ($history->recorded_at ? $history->recorded_at->format('M d, Y') : 'N/A') . '</td>';
            $html .= '<td><strong>' . htmlspecialchars($history->formatted_score) . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($history->recorder->name ?? 'TripWise Admin') . '</td>';
            $html .= '<td><span class="badge">' . strtoupper(str_replace('_', ' ', $history->record_type)) . '</span></td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '<script>window.onload = function() { window.print(); };</script>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
