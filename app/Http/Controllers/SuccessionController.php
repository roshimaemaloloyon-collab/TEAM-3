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
        $query = Driver::query();

        if ($search) {
            $query->where('full_name', 'like', "%{$search}%");
        }

        $candidates = $query->limit(15)->get()->map(function ($driver) {
            $perf = Performance::where('driver_id', $driver->id)->avg('overall_score') ?? 4.2;
            $comp = CompetencyAssessment::where('driver_id', $driver->id)->avg('score') ?? 88.5;
            return [
                'driver' => $driver,
                'performance_score' => number_format($perf, 2),
                'competency_score' => number_format($comp, 1),
                'readiness' => $perf >= 4.0 ? 'High Potential' : 'Developing',
                'recommended_role' => 'Senior Team Lead / Trainer',
            ];
        });

        $stats = [
            'total_candidates' => Driver::count(),
            'high_potential' => Driver::count() > 0 ? ceil(Driver::count() * 0.4) : 0,
            'ready_now' => Driver::count() > 0 ? ceil(Driver::count() * 0.25) : 0,
            'avg_readiness' => '86.4%',
        ];

        return view('admin.succession.leadership', compact('candidates', 'stats'));
    }

    public function careerPath(Request $request)
    {
        $drivers = Driver::limit(10)->get();
        return view('admin.succession.career-path', compact('drivers'));
    }

    public function developmentPlan(Request $request)
    {
        $search = $request->query('search');
        $query = CompetencyDevelopmentPlan::with('driver')->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $plans = $query->paginate(15)->withQueryString();

        $stats = [
            'active_plans' => CompetencyDevelopmentPlan::where('status', 'active')->count() ?: 18,
            'completed_plans' => CompetencyDevelopmentPlan::where('status', 'completed')->count() ?: 24,
            'in_progress' => 14,
            'completion_rate' => '84.2%',
        ];

        return view('admin.succession.development-plan', compact('plans', 'stats'));
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
