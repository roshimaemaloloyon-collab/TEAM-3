<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyAssessment;
use App\Models\Competency;
use App\Models\User;
use App\Models\Training;
use Illuminate\Http\Request;

class GapAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status'); // critical, moderate, minimal, proficient
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyAssessment::with(['driver', 'competency'])
            ->orderBy('score', 'asc');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->whereHas('competency', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        if ($status) {
            if ($status === 'critical') {
                $query->where('score', '<', 60);
            } elseif ($status === 'moderate') {
                $query->whereBetween('score', [60, 74.99]);
            } elseif ($status === 'minimal') {
                $query->whereBetween('score', [75, 84.99]);
            } elseif ($status === 'proficient') {
                $query->where('score', '>=', 85);
            }
        }

        $assessments = $query->paginate($perPage)->withQueryString();

        // Calculate real-time stats across all driver assessments
        $totalAssessments = CompetencyAssessment::count();
        $criticalGaps = CompetencyAssessment::where('score', '<', 60)->count();
        $moderateGaps = CompetencyAssessment::whereBetween('score', [60, 74.99])->count();
        $proficientCount = CompetencyAssessment::where('score', '>=', 85)->count();
        
        $avgCurrentScore = CompetencyAssessment::avg('score') ?? 0;
        $avgTargetScore = 85.0; // Benchmark target
        $overallGapPercentage = max(0, number_format($avgTargetScore - $avgCurrentScore, 1));

        $stats = [
            'total_assessments' => $totalAssessments,
            'critical_gaps' => $criticalGaps,
            'moderate_gaps' => $moderateGaps,
            'proficient_count' => $proficientCount,
            'avg_current_score' => number_format($avgCurrentScore, 1),
            'avg_target_score' => number_format($avgTargetScore, 1),
            'overall_gap' => $overallGapPercentage,
        ];

        // Competency Category Gaps Summary for Chart & Summary Cards
        $categoryGaps = Competency::leftJoin('competency_assessments', 'competencies.id', '=', 'competency_assessments.competency_id')
            ->selectRaw('competencies.category, competencies.name as competency_name, AVG(competency_assessments.score) as avg_actual, competencies.target_score')
            ->groupBy('competencies.id', 'competencies.category', 'competencies.name', 'competencies.target_score')
            ->get();

        $recommendedTrainings = Training::latest()->take(5)->get();
        $allDrivers = \App\Models\Driver::query()->notArchived()->orderBy('first_name')->get();

        return view('admin.competency.gap-analysis', compact(
            'assessments',
            'stats',
            'categoryGaps',
            'recommendedTrainings',
            'allDrivers'
        ));
    }

    public function exportGapPdf(Request $request)
    {
        $assessments = CompetencyAssessment::with(['driver', 'competency'])
            ->orderBy('score', 'asc')
            ->get();

        $filename = "competency_gap_analysis_report.pdf";

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<title>Competency Gap Analysis Report — TripWise TNVS</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 30px; max-width: 900px; margin: 0 auto; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #063151; margin: 0; font-size: 20px; text-transform: uppercase; }';
        $html .= '.header p { color: #64748b; margin: 4px 0 0; font-size: 11px; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 15px; }';
        $html .= 'th { background: #063151; color: #ffffff; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; }';
        $html .= 'td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }';
        $html .= '.badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: bold; font-size: 10px; }';
        $html .= '.badge-danger { background: #fee2e2; color: #991b1b; }';
        $html .= '.badge-warning { background: #fef3c7; color: #92400e; }';
        $html .= '.badge-success { background: #d1fae5; color: #065f46; }';
        $html .= '@media print { body { padding: 0; } }';
        $html .= '</style></head><body>';

        $html .= '<div class="header"><h1>TRIPWISE TNVS — COMPETENCY GAP ANALYSIS REPORT</h1><p>Official Performance Gap & Training Intervention Evaluation</p></div>';

        $html .= '<table><thead><tr><th>Driver Name</th><th>Competency Skill</th><th>Target Score</th><th>Actual Score</th><th>Skill Gap</th><th>Gap Severity</th><th>Recommended Action</th></tr></thead><tbody>';
        foreach ($assessments as $a) {
            $target = $a->competency->target_score ?? 85;
            $actual = $a->score ?? 0;
            $gap = max(0, round($target - $actual, 1));
            $driverName = $a->driver->name ?? 'Driver #' . $a->driver_id;
            $compName = $a->competency->name ?? 'Operational Safety';

            if ($actual < 60) {
                $badge = '<span class="badge badge-danger">Critical Gap</span>';
                $action = 'Mandatory Re-training';
            } elseif ($actual < 75) {
                $badge = '<span class="badge badge-warning">Moderate Gap</span>';
                $action = 'Assigned Mentorship';
            } elseif ($actual < 85) {
                $badge = '<span class="badge badge-warning">Minimal Gap</span>';
                $action = 'Refresher Course';
            } else {
                $badge = '<span class="badge badge-success">Proficient</span>';
                $action = 'Skill Maintenance';
            }

            $html .= '<tr>';
            $html .= '<td><strong>' . htmlspecialchars($driverName) . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($compName) . '</td>';
            $html .= '<td>' . number_format($target, 1) . '%</td>';
            $html .= '<td><strong>' . number_format($actual, 1) . '%</strong></td>';
            $html .= '<td style="color:' . ($gap > 15 ? '#dc2626' : '#d97706') . ';font-weight:bold;">-' . $gap . '%</td>';
            $html .= '<td>' . $badge . '</td>';
            $html .= '<td>' . htmlspecialchars($action) . '</td>';
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
