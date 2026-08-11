<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Performance;
use App\Models\CompetencyAssessment;
use App\Models\Training;
use App\Models\TrainingRegistration;
use App\Models\PeerEvaluation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverApiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Driver::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return response()->json([
            'success' => true,
            'drivers' => $query->latest()->paginate(15)
        ]);
    }

    public function show(Driver $driver)
    {
        return response()->json([
            'success' => true,
            'driver' => $driver
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email|unique:drivers,email',
            'branch' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'vehicle_assignment' => 'nullable|string',
        ]);

        $driver = Driver::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver created successfully.',
            'driver' => $driver
        ], 201);
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'gender' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'branch' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'vehicle_assignment' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $driver->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver updated successfully.',
            'driver' => $driver
        ]);
    }

    public function updateStatus(Request $request, Driver $driver)
    {
        $request->validate(['status' => 'required|string']);
        $driver->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Driver status updated successfully.',
            'driver' => $driver
        ]);
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return response()->json([
            'success' => true,
            'message' => 'Driver deleted successfully.'
        ]);
    }

    public function export()
    {
        return response()->json([
            'success' => true,
            'message' => 'Export endpoint ready.',
            'data' => Driver::all()
        ]);
    }

    public function dashboard(Request $request)
    {
        $driver = $this->resolveDriver($request);

        $performanceTrend = Performance::where('driver_id', $driver->id)
            ->selectRaw('DATE_FORMAT(created_at, "%b %Y") as month, AVG(score) as avg_score')
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();

        $myPerformance = Performance::where('driver_id', $driver->id)->latest()->first();
        $myAvgScore = PeerEvaluation::where('driver_id', $driver->id)->avg('score') ?? 4.6;
        $myOverallScore = $myPerformance?->score ?? $driver->performance_score ?? 4.9;
        $myCertificates = TrainingRegistration::where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->count();
        $myTrainings = Training::whereHas('registrations', function ($q) use ($driver) {
            $q->where('driver_id', $driver->id);
        })->count();
        $myCompletedTrainings = TrainingRegistration::where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->count();
        $myAttendanceRate = TrainingRegistration::where('driver_id', $driver->id)
            ->where('status', '!=', 'pending')
            ->count();
        $myEvaluations = PeerEvaluation::where('driver_id', $driver->id)->count();
        $upcomingTrainings = TrainingRegistration::where('driver_id', $driver->id)
            ->where('status', 'upcoming')
            ->count();

        return response()->json([
            'driver' => $driver,
            'performance_trend' => $performanceTrend,
            'my_performance' => $myPerformance,
            'my_overall_score' => $myOverallScore,
            'my_avg_score' => $myAvgScore,
            'my_certificates' => $myCertificates,
            'my_trainings' => $myTrainings,
            'my_completed_trainings' => $myCompletedTrainings,
            'my_attendance_rate' => $myAttendanceRate,
            'my_evaluations' => $myEvaluations,
            'upcoming_trainings' => $upcomingTrainings,
        ]);
    }

    public function profile(Request $request)
    {
        $driver = $this->resolveDriver($request);

        return response()->json([
            'driver' => $driver,
            'recent_performance' => Performance::where('driver_id', $driver->id)
                ->latest()
                ->take(5)
                ->get(),
            'recent_evaluations' => PeerEvaluation::where('driver_id', $driver->id)
                ->with('evaluator')
                ->latest()
                ->take(5)
                ->get(),
            'upcoming_trainings' => Training::whereHas('registrations', function ($q) use ($driver) {
                $q->where('driver_id', $driver->id)->where('status', 'upcoming');
            })->get(),
            'certificates' => TrainingRegistration::where('driver_id', $driver->id)
                ->where('status', 'completed')
                ->with('training')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $driver = $this->resolveDriver($request);

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'emergency_contact_person' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:30',
        ]);

        $driver->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'driver' => $driver,
        ]);
    }

    public function myPerformance(Request $request)
    {
        $driver = $this->resolveDriver($request);

        return response()->json([
            'performance' => Performance::where('driver_id', $driver->id)
                ->latest()
                ->paginate(20),
        ]);
    }

    public function myCompetency(Request $request)
    {
        $driver = $this->resolveDriver($request);

        return response()->json([
            'competencies' => CompetencyAssessment::where('driver_id', $driver->id)
                ->latest()
                ->get(),
        ]);
    }

    public function myEvaluations(Request $request)
    {
        $driver = $this->resolveDriver($request);

        return response()->json([
            'evaluations' => PeerEvaluation::where('driver_id', $driver->id)
                ->with('evaluator')
                ->latest()
                ->paginate(20),
        ]);
    }

    public function myNotifications(Request $request)
    {
        $driver = $this->resolveDriver($request);

        return response()->json([
            'notifications' => Notification::where('driver_id', $driver->id)
                ->latest()
                ->paginate(20),
        ]);
    }

    public function myReports(Request $request)
    {
        $driver = $this->resolveDriver($request);

        return response()->json([
            'reports' => \App\Models\Report::where('driver_id', $driver->id)
                ->latest()
                ->paginate(20),
        ]);
    }

    private function resolveDriver(Request $request): Driver
    {
        $user = Auth::user();

        $driver = Driver::where('user_id', $user->id)->first()
            ?? Driver::where('contact_number', $user->phone)->first()
            ?? Driver::where('email', $user->email)->first()
            ?? Driver::first();

        return $driver;
    }
}
