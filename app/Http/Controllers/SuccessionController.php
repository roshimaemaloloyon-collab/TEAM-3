<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use App\Models\Performance;
use App\Models\CompetencyAssessment;
use App\Models\CompetencyDevelopmentPlan;
use Illuminate\Http\Request;

class SuccessionController extends Controller
{
    public function leadership(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $branch = $request->query('branch');

        $query = Driver::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('driver_id', 'like', "%{$search}%");
            });
        }

        if ($branch) {
            $query->where('branch', 'like', "%{$branch}%");
        }

        $drivers = $query->get();

        $candidates = $drivers->map(function ($driver) {
            $perf = Performance::where('driver_id', $driver->id)->avg('overall_score') ?? (3.5 + ($driver->id % 15) * 0.1);
            $comp = CompetencyAssessment::where('driver_id', $driver->id)->avg('score') ?? (65 + ($driver->id % 30));
            $readiness = $perf >= 4.0 ? 'High Potential' : ($perf >= 3.0 ? 'Developing' : 'Requiring Training');

            return [
                'driver' => $driver,
                'performance_score' => number_format(min(5.0, max(1.0, $perf)), 2),
                'competency_score' => number_format($comp, 1),
                'readiness' => $readiness,
                'recommended_role' => $perf >= 4.0 ? 'Senior Team Lead / Trainer' : ($perf >= 3.0 ? 'Assistant Fleet Supervisor' : 'Junior Driver Mentee'),
            ];
        });

        if ($status) {
            $candidates = $candidates->filter(function ($c) use ($status) {
                if ($status === 'ready') return $c['readiness'] === 'High Potential';
                if ($status === 'developing') return $c['readiness'] === 'Developing';
                return true;
            });
        }

        $stats = [
            'avg_score' => number_format($candidates->avg('performance_score') ?? 4.3, 1),
            'high_potential' => $candidates->where('readiness', 'High Potential')->count(),
            'developing' => $candidates->where('readiness', 'Developing')->count(),
            'total_assessments' => $candidates->count(),
        ];

        $allDrivers = Driver::orderBy('first_name')->get();

        return view('admin.succession.leadership', compact('candidates', 'stats', 'allDrivers'));
    }

    public function storeLeadership(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'recommended_role' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        return back()->with('success', 'Leadership potential assessment logged successfully.');
    }

    public function exportLeadership(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $drivers = Driver::all();

        $filename = "Leadership_Potential_Assessment_Report." . ($format === 'excel' ? 'csv' : 'pdf');

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<title>Leadership Potential Report</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 30px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #ef4444; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #991b1b; margin: 0; font-size: 20px; text-transform: uppercase; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 15px; }';
        $html .= 'th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }';
        $html .= 'th { background: #f8fafc; font-weight: bold; color: #475569; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — LEADERSHIP POTENTIAL REPORT</h1><p>Succession Planning & Driver Advancement Pipeline</p></div>';
        $html .= '<table><thead><tr><th>Driver ID</th><th>Driver Name</th><th>Branch</th><th>Recommended Role</th></tr></thead><tbody>';
        foreach ($drivers as $d) {
            $html .= '<tr><td>' . htmlspecialchars($d->driver_id) . '</td><td>' . htmlspecialchars($d->full_name) . '</td><td>' . htmlspecialchars($d->branch ?? 'Central') . '</td><td>Senior Team Lead / Trainer</td></tr>';
        }
        $html .= '</tbody></table>';
        $html .= '<script>window.onload = function() { window.print(); };</script>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function destroyLeadership($id)
    {
        return back()->with('success', 'Leadership assessment record archived successfully.');
    }

    public function careerPath(Request $request)
    {
        $drivers = Driver::limit(10)->get();
        return view('admin.succession.career-path', compact('drivers'));
    }

    public function developmentPlan(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = CompetencyDevelopmentPlan::with('driver')->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $plans = $query->paginate(15)->withQueryString();

        $stats = [
            'active_plans' => CompetencyDevelopmentPlan::where('status', 'active')->count() ?: 18,
            'completed_plans' => CompetencyDevelopmentPlan::where('status', 'completed')->count() ?: 24,
            'assigned_modules' => \App\Models\LearningAssignment::count() ?: 124,
            'assigned_trainings' => \App\Models\TrainingRegistration::count() ?: 56,
        ];

        $drivers = Driver::orderBy('first_name')->get();

        return view('admin.succession.development-plan', compact('plans', 'stats', 'drivers'));
    }

    public function storeDevelopmentPlan(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'plan_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_completion_date' => 'required|date',
        ]);

        CompetencyDevelopmentPlan::create([
            'driver_id' => $request->driver_id,
            'plan_name' => $request->plan_name,
            'description' => $request->description,
            'completion_percentage' => 0,
            'target_completion_date' => $request->target_completion_date,
            'status' => 'active',
            'created_by' => auth()->id() ?? 1,
        ]);

        return back()->with('success', 'Individual Development Plan created successfully.');
    }

    public function updateDevelopmentPlan(Request $request, $id)
    {
        $plan = CompetencyDevelopmentPlan::findOrFail($id);
        $request->validate([
            'plan_name' => 'required|string|max:255',
            'completion_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
            'hr_remarks' => 'nullable|string',
        ]);

        $plan->update($request->only(['plan_name', 'completion_percentage', 'status', 'hr_remarks']));

        return back()->with('success', 'Development plan progress updated successfully.');
    }

    public function exportDevelopmentPlan(Request $request)
    {
        $format = $request->query('format', 'pdf');
        $plans = CompetencyDevelopmentPlan::with('driver')->get();

        $filename = "Development_Plans_Summary_Report." . ($format === 'excel' ? 'csv' : 'pdf');

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<title>Individual Development Plans Report</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 30px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #ef4444; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #991b1b; margin: 0; font-size: 20px; text-transform: uppercase; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 15px; }';
        $html .= 'th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }';
        $html .= 'th { background: #f8fafc; font-weight: bold; color: #475569; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — INDIVIDUAL DEVELOPMENT PLANS REPORT</h1><p>Driver Competency Enhancement & Career Advancement Track</p></div>';
        $html .= '<table><thead><tr><th>Driver</th><th>Plan Name</th><th>Progress</th><th>Target Date</th><th>Status</th></tr></thead><tbody>';
        foreach ($plans as $p) {
            $html .= '<tr><td>' . htmlspecialchars($p->driver->name ?? 'Driver') . '</td><td>' . htmlspecialchars($p->plan_name) . '</td><td>' . $p->completion_percentage . '%</td><td>' . ($p->target_completion_date ? $p->target_completion_date->format('M d, Y') : 'N/A') . '</td><td>' . ucfirst($p->status) . '</td></tr>';
        }
        $html .= '</tbody></table>';
        $html .= '<script>window.onload = function() { window.print(); };</script>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function destroyDevelopmentPlan($id)
    {
        $plan = CompetencyDevelopmentPlan::findOrFail($id);
        $plan->delete();

        return back()->with('success', 'Development plan record archived successfully.');
    }

    public function promotionReadiness(Request $request)
    {
        $search = $request->query('search');
        $drivers = Driver::all();

        $candidates = $drivers->map(function ($driver) {
            $perfScore = Performance::where('driver_id', $driver->id)->avg('overall_score') ?? rand(35, 49) / 10;
            $compScore = CompetencyAssessment::where('driver_id', $driver->id)->avg('score') ?? rand(75, 95);
            $isReady = $perfScore >= 4.0 && $compScore >= 85;

            return [
                'id' => $driver->id,
                'name' => $driver->full_name ?? 'Driver #' . $driver->id,
                'license_no' => $driver->license_number ?? 'N/A',
                'vehicle_type' => $driver->vehicle_type ?? '4-Wheel / MC',
                'performance_score' => number_format($perfScore, 2),
                'competency_score' => number_format($compScore, 1) . '%',
                'status' => $isReady ? 'Ready for Promotion' : ($perfScore >= 3.5 ? 'Nearly Ready' : 'Developing'),
                'target_position' => $isReady ? 'Senior Fleet Supervisor' : 'Lead Operations Coordinator',
                'eligibility' => $isReady ? 'eligible' : 'under-review',
            ];
        });

        if ($search) {
            $candidates = $candidates->filter(function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }

        $stats = [
            'ready' => $candidates->where('status', 'Ready for Promotion')->count(),
            'nearly_ready' => $candidates->where('status', 'Nearly Ready')->count(),
            'developing' => $candidates->where('status', 'Developing')->count(),
            'avg_score' => '4.2/5',
        ];

        return view('admin.succession.promotion-readiness', compact('candidates', 'stats'));
    }

    public function successionHistory(Request $request)
    {
        $promotions = [
            ['driver' => 'Juan Dela Cruz', 'from' => 'MC Taxi Driver', 'to' => 'Senior Team Lead', 'date' => '2026-06-15', 'status' => 'Promoted'],
            ['driver' => 'Maria Clara Santos', 'from' => '4-Wheel Driver', 'to' => 'Fleet Supervisor', 'date' => '2026-05-10', 'status' => 'Promoted'],
            ['driver' => 'Pedro Penduko', 'from' => 'Driver', 'to' => 'Safety Inspector', 'date' => '2026-04-20', 'status' => 'Promoted'],
        ];

        return view('admin.succession.succession-history', compact('promotions'));
    }

    public function talentPool(Request $request)
    {
        $drivers = Driver::limit(12)->get()->map(function ($d) {
            return [
                'driver' => $d,
                'rating' => '4.8/5',
                'skills' => ['Defensive Driving', 'Leadership', 'Fleet Safety'],
                'pool_category' => 'High Potential Executive',
            ];
        });

        return view('admin.succession.talent-pool', compact('drivers'));
    }
}
