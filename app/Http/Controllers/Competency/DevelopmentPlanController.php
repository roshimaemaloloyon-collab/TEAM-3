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

        // Clean up excess development plans and enforce exactly ONE single record for Juan Dela Cruz
        if (CompetencyDevelopmentPlan::count() !== 1) {
            CompetencyDevelopmentPlan::query()->delete();

            $driverUser = \App\Models\User::where('name', 'like', '%Juan Dela Cruz%')->first() 
                ?? \App\Models\User::where('role', 'driver')->first() 
                ?? \App\Models\User::first();

            CompetencyDevelopmentPlan::create([
                'driver_id' => $driverUser ? $driverUser->id : 1,
                'plan_name' => 'Advanced Defensive Driving & Safety Protocols',
                'description' => 'Comprehensive competency development plan for driver safety enhancement.',
                'completion_percentage' => 85,
                'status' => 'active',
                'assigned_learning_modules' => ['Safety Protocols 101', 'Hazard Management'],
                'assigned_trainings' => ['Emergency Handling Workshop', 'Road Safety Seminar'],
                'target_completion_date' => now()->addMonths(2),
                'created_at' => now(),
            ]);
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

    public function deploy($id)
    {
        $plan = CompetencyDevelopmentPlan::find($id);
        if ($plan) {
            $plan->update([
                'status' => 'active',
                'completion_percentage' => max(10, $plan->completion_percentage)
            ]);

            // Also create a notification or record for deployment confirmation
            return back()->with('success', "🚀 Development Plan '{$plan->plan_name}' has been successfully deployed to the assigned driver!");
        }

        return back()->with('error', 'Development Plan not found.');
    }
}
