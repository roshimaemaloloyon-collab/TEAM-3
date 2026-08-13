<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyDevelopmentPlan;
use Illuminate\Http\Request;

class DevelopmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        // Auto-seed initial realistic Development Plans for drivers if table is empty
        if (CompetencyDevelopmentPlan::count() === 0) {
            $drivers = \App\Models\Driver::query()->notArchived()->orderBy('id')->get();
            $driverCount = $drivers->count();

            if ($driverCount > 0) {
                $samplePlans = [
                    ['name' => 'Advanced Defensive Driving & Safety Protocols', 'progress' => 85, 'status' => 'active', 'modules' => ['Safety 101', 'Hazard Mgmt'], 'trainings' => ['Emergency Handling Workshop']],
                    ['name' => 'GPS Route Optimization & Navigation Mastery', 'progress' => 60, 'status' => 'active', 'modules' => ['GPS Systems', 'Traffic Patterns'], 'trainings' => ['City Traffic Navigation']],
                    ['name' => 'Customer Experience & Executive Passenger Service', 'progress' => 100, 'status' => 'completed', 'modules' => ['Passenger Etiquette', 'Conflict Resolution'], 'trainings' => ['Customer Service Excellence']],
                    ['name' => 'LTFRB Regulatory Compliance & Inspection Course', 'progress' => 40, 'status' => 'on_hold', 'modules' => ['LTFRB Guidelines', 'Vehicle Checklists'], 'trainings' => ['Road Readiness Seminar']],
                    ['name' => 'Night Operations & Weather Driving Protocol', 'progress' => 75, 'status' => 'active', 'modules' => ['Night Driving', 'Rain Safety'], 'trainings' => ['Wet Asphalt Braking Course']],
                    ['name' => 'First Aid & Medical Emergency Response Program', 'progress' => 90, 'status' => 'active', 'modules' => ['Basic CPR', 'Evacuation Steps'], 'trainings' => ['Red Cross First Aid Course']],
                ];

                $adminUser = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();

                foreach ($samplePlans as $idx => $p) {
                    $driver = $drivers[$idx % $driverCount];
                    $driverUser = ($driver && $driver->user_id) ? \App\Models\User::find($driver->user_id) : null;
                    $driverId = $driverUser ? $driverUser->id : ($adminUser ? $adminUser->id : 1);

                    CompetencyDevelopmentPlan::create([
                        'driver_id' => $driverId,
                        'plan_name' => $p['name'],
                        'description' => 'Comprehensive competency development plan.',
                        'completion_percentage' => $p['progress'],
                        'status' => $p['status'],
                        'assigned_learning_modules' => $p['modules'],
                        'assigned_trainings' => $p['trainings'],
                        'target_completion_date' => now()->addMonths(2),
                        'created_by' => $adminUser ? $adminUser->id : null,
                        'created_at' => now()->subDays(5 * ($idx + 1)),
                    ]);
                }
            }
        }

        $query = CompetencyDevelopmentPlan::with('driver')->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('driver', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhere('plan_name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $plans = $query->paginate($perPage)->withQueryString();

        $stats = [
            'active' => CompetencyDevelopmentPlan::where('status', 'active')->count(),
            'completed' => CompetencyDevelopmentPlan::where('status', 'completed')->count(),
            'on_hold' => CompetencyDevelopmentPlan::where('status', 'on_hold')->count(),
            'avg_progress' => number_format(CompetencyDevelopmentPlan::avg('completion_percentage') ?? 0, 1) . '%',
        ];

        if (config('database.default') === 'pgsql') {
            $progressData = CompetencyDevelopmentPlan::selectRaw("TO_CHAR(created_at, 'MM') as month_num, AVG(completion_percentage) as avg_progress")
                ->whereNotNull('created_at')
                ->groupByRaw("TO_CHAR(created_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $progressData = CompetencyDevelopmentPlan::selectRaw('strftime("%m", created_at) as month_num, AVG(completion_percentage) as avg_progress')
                ->whereNotNull('created_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }

        $trainingData = CompetencyDevelopmentPlan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        $allDrivers = \App\Models\Driver::query()->notArchived()->orderBy('first_name')->get();

        return view('admin.competency.development-plan', compact('plans', 'stats', 'progressData', 'trainingData', 'allDrivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required',
            'plan_name' => 'required|string',
            'completion_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|string'
        ]);

        $driver = \App\Models\Driver::find($request->driver_id);
        $userId = null;
        if ($driver && $driver->user_id && \App\Models\User::where('id', $driver->user_id)->exists()) {
            $userId = $driver->user_id;
        } elseif (\App\Models\User::where('id', $request->driver_id)->exists()) {
            $userId = (int)$request->driver_id;
        } else {
            $firstUser = \App\Models\User::first();
            $userId = $firstUser ? $firstUser->id : auth()->id();
        }

        CompetencyDevelopmentPlan::create([
            'driver_id' => $userId,
            'plan_name' => $validated['plan_name'],
            'completion_percentage' => $request->input('completion_percentage', 0),
            'status' => $request->input('status', 'active'),
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        return back()->with('success', 'Development Plan created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string',
            'completion_percentage' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|string'
        ]);

        $plan = CompetencyDevelopmentPlan::find($id);
        if ($plan) {
            $plan->update([
                'plan_name' => $validated['plan_name'],
                'completion_percentage' => $request->input('completion_percentage', $plan->completion_percentage),
                'status' => $request->input('status', $plan->status),
            ]);
        }

        return back()->with('success', 'Development Plan updated successfully.');
    }
}
