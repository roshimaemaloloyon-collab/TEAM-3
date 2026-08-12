<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class KpiMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Driver::query()->notArchived();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('driver_id', 'like', "%{$search}%")
                  ->orWhere('vehicle_assignment', 'like', "%{$search}%");
            });
        }

        if ($category) {
            if ($category === 'safety') {
                $query->where('performance_score', '>=', 4.8);
            } elseif ($category === 'attendance') {
                $query->where('trips_count', '>=', 100);
            } elseif ($category === 'customer_service') {
                $query->where('complaints_count', 0);
            }
        }

        $drivers = $query->orderByDesc('performance_score')->paginate($perPage)->withQueryString();

        $allCount = Driver::query()->notArchived()->count();
        $achievedCount = Driver::query()->notArchived()->where('performance_score', '>=', 4.5)->count();

        $stats = [
            'avg_kpi' => '94.2%',
            'target_achievement' => "{$achievedCount} / {$allCount}",
            'meeting_kpi' => $achievedCount,
            'below_target' => max(0, $allCount - $achievedCount),
        ];

        return view('admin.performance.kpi-monitoring', compact('drivers', 'stats'));
    }

    public function export(Request $request)
    {
        $format = strtolower($request->input('format', 'csv'));
        $drivers = Driver::query()->notArchived()->orderByDesc('performance_score')->get();

        if ($format === 'pdf') {
            return $this->exportPdf($drivers);
        }

        $filename = "driver_kpi_monitoring_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Driver ID', 'Driver Name', 'KPI Score', 'Monthly Target', 'Progress %', 'Achievement %', 'Status'];

        $callback = function() use($drivers, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($drivers as $driver) {
                $score = $driver->performance_score ?? 4.5;
                $pct = min(100, intval(($score / 5.0) * 100));
                $isAchieved = $score >= 4.5;

                fputcsv($file, [
                    $driver->formatted_id,
                    $driver->full_name,
                    number_format($score, 1) . ' / 5.0',
                    '4.5 / 5.0',
                    $pct . '%',
                    $pct . '%',
                    $isAchieved ? 'Target Achieved' : 'Pending Target'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($drivers)
    {
        $filename = "driver_kpi_monitoring_report_" . date('Y-m-d') . ".pdf";

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>KPI Monitoring Report</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; padding: 20px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #063151; margin: 0; font-size: 22px; }';
        $html .= '.header p { color: #64748b; margin: 4px 0 0; font-size: 12px; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= 'th { background: #063151; color: #ffffff; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; }';
        $html .= 'td { padding: 8px 6px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }';
        $html .= '.badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-weight: bold; font-size: 9px; }';
        $html .= '.badge-achieved { background: #d1fae5; color: #065f46; }';
        $html .= '.badge-pending { background: #fef3c7; color: #92400e; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — KPI MONITORING REPORT</h1><p>Generated on ' . date('F d, Y h:i A') . ' | Key Performance Indicator Metrics</p></div>';
        $html .= '<table><thead><tr><th>Driver ID</th><th>Driver Name</th><th>KPI Score</th><th>Monthly Target</th><th>Progress %</th><th>Achievement Status</th></tr></thead><tbody>';

        foreach ($drivers as $driver) {
            $score = $driver->performance_score ?? 4.5;
            $pct = min(100, intval(($score / 5.0) * 100));
            $isAchieved = $score >= 4.5;
            $badgeClass = $isAchieved ? 'badge-achieved' : 'badge-pending';
            $statusLabel = $isAchieved ? 'TARGET ACHIEVED' : 'PENDING TARGET';

            $html .= '<tr>';
            $html .= '<td><strong>' . htmlspecialchars($driver->formatted_id) . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($driver->full_name) . '</td>';
            $html .= '<td><strong>' . number_format($score, 1) . ' / 5.0</strong></td>';
            $html .= '<td>4.5 / 5.0</td>';
            $html .= '<td>' . $pct . '%</td>';
            $html .= '<td><span class="badge ' . $badgeClass . '">' . $statusLabel . '</span></td>';
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
