<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Performance;
use Illuminate\Http\Request;

class PerformanceApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Performance::with('driver');

        if ($search = $request->input('search')) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('performance_status', $status);
        }

        if ($driverId = $request->input('driver_id')) {
            $query->where('driver_id', $driverId);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $performances = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($performances, 'Performance records retrieved successfully.', 200, [
            'total' => $performances->total(),
            'current_page' => $performances->currentPage(),
            'last_page' => $performances->lastPage(),
            'per_page' => $performances->perPage(),
        ]);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $performance = Performance::with('driver')->find($id);

        if (! $performance) {
            return $this->error('Performance record not found.', 404);
        }

        return $this->success([
            'performance' => $performance,
            'rating_label' => $performance->getOverallRating(),
            'rating_color' => $performance->getOverallRatingColor(),
            'safety_rating' => $performance->getSafetyRating(),
            'complaint_rate' => $performance->getComplaintRate(),
            'commendations_rate' => $performance->getCommendationsRate(),
            'is_top_performer' => $performance->isTopPerformer(),
            'is_underperforming' => $performance->isUnderperforming(),
        ], 'Performance record retrieved successfully.');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'driver_id' => 'required|integer|exists:users,id',
            'customer_rating' => 'nullable|numeric|min:0|max:5',
            'peer_evaluation_score' => 'nullable|numeric|min:0|max:100',
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'trip_completion_rate' => 'nullable|numeric|min:0|max:100',
            'cancellation_rate' => 'nullable|numeric|min:0|max:100',
            'safety_score' => 'nullable|numeric|min:0|max:100',
            'complaints_count' => 'nullable|integer|min:0',
            'commendations_count' => 'nullable|integer|min:0',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'performance_status' => 'nullable|string',
            'ranking' => 'nullable|integer',
            'recorded_at' => 'nullable|date',
            'recorded_by' => 'nullable|integer',
        ]);

        $performance = Performance::create($validated);
        $performance->updatePerformanceStatus();

        return $this->success([
            'performance' => $performance->fresh(),
            'rating_label' => $performance->getOverallRating(),
            'rating_color' => $performance->getOverallRatingColor(),
        ], 'Performance record created successfully.', 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $performance = Performance::findOrFail($id);

        $validated = $request->validate([
            'customer_rating' => 'nullable|numeric|min:0|max:5',
            'peer_evaluation_score' => 'nullable|numeric|min:0|max:100',
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'trip_completion_rate' => 'nullable|numeric|min:0|max:100',
            'cancellation_rate' => 'nullable|numeric|min:0|max:100',
            'safety_score' => 'nullable|numeric|min:0|max:100',
            'complaints_count' => 'nullable|integer|min:0',
            'commendations_count' => 'nullable|integer|min:0',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'performance_status' => 'nullable|string',
            'ranking' => 'nullable|integer',
            'recorded_at' => 'nullable|date',
            'recorded_by' => 'nullable|integer',
        ]);

        $performance->update($validated);
        $performance->updatePerformanceStatus();

        return $this->success([
            'performance' => $performance->fresh(),
            'rating_label' => $performance->getOverallRating(),
            'rating_color' => $performance->getOverallRatingColor(),
        ], 'Performance record updated successfully.');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $performance = Performance::findOrFail($id);
        $performance->delete();

        return $this->success(null, 'Performance record deleted successfully.');
    }

    public function stats(): \Illuminate\Http\JsonResponse
    {
        return $this->success([
            'avg_score' => Performance::avg('overall_score') ? number_format(Performance::avg('overall_score'), 2) : '0.00',
            'top_drivers' => Performance::where('performance_status', 'excellent')->count(),
            'good_drivers' => Performance::where('performance_status', 'good')->count(),
            'average_drivers' => Performance::where('performance_status', 'average')->count(),
            'needs_improvement' => Performance::where('performance_status', 'needs_improvement')->count(),
            'avg_rating' => number_format(Performance::avg('customer_rating'), 2),
            'avg_safety_score' => number_format(Performance::avg('safety_score'), 2),
        ], 'Performance stats retrieved successfully.');
    }
}