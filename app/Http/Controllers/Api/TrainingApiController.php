<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Training::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $trainings = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($trainings, 'Trainings retrieved successfully.', 200, [
            'total' => $trainings->total(),
            'current_page' => $trainings->currentPage(),
            'last_page' => $trainings->lastPage(),
            'per_page' => $trainings->perPage(),
        ]);
    }

    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        $totalTrainings = Training::count();
        $upcomingTrainings = Training::where('status', 'upcoming')->count();
        $ongoingTrainings = Training::where('status', 'ongoing')->count();
        $completedTrainings = Training::where('status', 'completed')->count();
        $registeredDrivers = \App\Models\TrainingRegistration::count();
        $attendanceRate = \App\Models\Attendance::where('status', 'present')->count() / max(\App\Models\Attendance::count(), 1) * 100;
        $certificatesIssued = \App\Models\Certificate::count();
        $avgTrainingScore = \App\Models\TrainingEvaluation::avg('overall_rating');

        return $this->success([
            'total_trainings' => $totalTrainings,
            'upcoming_trainings' => $upcomingTrainings,
            'ongoing_trainings' => $ongoingTrainings,
            'completed_trainings' => $completedTrainings,
            'registered_drivers' => $registeredDrivers,
            'attendance_rate' => round($attendanceRate, 2),
            'certificates_issued' => $certificatesIssued,
            'average_training_score' => round($avgTrainingScore, 2),
        ], 'Dashboard data retrieved successfully.');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
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
            return $this->error('Validation failed.', 422, $validator->errors());
        }

        $training = Training::create($request->all());

        return $this->success($training, 'Training created successfully.', 201);
    }

    public function show(Training $training): \Illuminate\Http\JsonResponse
    {
        return $this->success([
            'training' => $training,
            'status_label' => $training->getStatusLabel(),
            'status_color' => $training->getStatusColor(),
            'is_full' => $training->isFull(),
            'available_slots' => $training->getAvailableSlots(),
            'progress' => $training->getProgress(),
            'duration_hours' => $training->getDurationHours(),
            'attendance_rate' => $training->getAttendanceRate(),
            'average_rating' => $training->getAverageRating(),
            'certificates_issued' => $training->getCertificatesIssued(),
        ], 'Training retrieved successfully.');
    }

    public function update(Request $request, Training $training): \Illuminate\Http\JsonResponse
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
            return $this->error('Validation failed.', 422, $validator->errors());
        }

        if (!$training->canBeEdited()) {
            return $this->error('This training cannot be edited.', 422);
        }

        $training->update($request->all());

        return $this->success([
            'training' => $training->fresh(),
            'status_label' => $training->getStatusLabel(),
            'status_color' => $training->getStatusColor(),
        ], 'Training updated successfully.');
    }

    public function destroy(Training $training): \Illuminate\Http\JsonResponse
    {
        if (!$training->canBeCancelled()) {
            return $this->error('This training cannot be cancelled.', 422);
        }

        $training->delete();

        return $this->success(null, 'Training deleted successfully.');
    }
}