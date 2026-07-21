<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $trainings = Training::query()
            ->when($request->filled('search'), function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
            })
            ->when($request->filled('category'), fn ($q, $category) => $q->where('category', $category))
            ->when($request->filled('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return response()->json($trainings);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'required|string|max:255',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'status' => 'in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $training = Training::create($request->all());

        return response()->json($training, 201);
    }

    public function show(Training $training): JsonResponse
    {
        return response()->json($training->load('registrations.driver', 'attendance.driver', 'evaluations.driver', 'certificates.driver'));
    }

    public function update(Request $request, Training $training): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'sometimes|string|max:255',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'start_datetime' => 'sometimes|date',
            'end_datetime' => 'sometimes|date|after:start_datetime',
            'status' => 'in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $training->update($request->all());

        return response()->json($training);
    }

    public function destroy(Training $training): JsonResponse
    {
        $training->delete();

        return response()->json(null, 204);
    }

    public function dashboard(): JsonResponse
    {
        $totalTrainings = Training::count();
        $upcomingTrainings = Training::where('status', 'upcoming')->count();
        $ongoingTrainings = Training::where('status', 'ongoing')->count();
        $completedTrainings = Training::where('status', 'completed')->count();
        $registeredDrivers = \App\Models\TrainingRegistration::count();
        $attendanceRate = \App\Models\Attendance::where('status', 'present')->count() / max(\App\Models\Attendance::count(), 1) * 100;
        $certificatesIssued = \App\Models\Certificate::count();
        $avgTrainingScore = \App\Models\TrainingEvaluation::avg('overall_rating');

        return response()->json([
            'total_trainings' => $totalTrainings,
            'upcoming_trainings' => $upcomingTrainings,
            'ongoing_trainings' => $ongoingTrainings,
            'completed_trainings' => $completedTrainings,
            'registered_drivers' => $registeredDrivers,
            'attendance_rate' => round($attendanceRate, 2),
            'certificates_issued' => $certificatesIssued,
            'average_training_score' => round($avgTrainingScore, 2),
        ]);
    }
}
