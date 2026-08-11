<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\CompetencyAssessment;
use Illuminate\Http\Request;

class CompetencyApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = CompetencyAssessment::with('driver');

        if ($search = $request->input('search')) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($driverId = $request->input('driver_id')) {
            $query->where('driver_id', $driverId);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $assessments = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($assessments, 'Competency assessments retrieved successfully.', 200, [
            'total' => $assessments->total(),
            'current_page' => $assessments->currentPage(),
            'last_page' => $assessments->lastPage(),
            'per_page' => $assessments->perPage(),
        ]);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $assessment = CompetencyAssessment::with('driver')->find($id);

        if (! $assessment) {
            return $this->error('Assessment not found.', 404);
        }

        return $this->success($assessment, 'Assessment retrieved successfully.');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'driver_id' => 'required|integer',
            'score' => 'required|numeric|min:0|max:100',
            'category' => 'nullable|string',
            'assessor_id' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $assessment = CompetencyAssessment::create($validated);

        return $this->success($assessment, 'Assessment created successfully.', 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $assessment = CompetencyAssessment::findOrFail($id);

        $validated = $request->validate([
            'score' => 'nullable|numeric|min:0|max:100',
            'category' => 'nullable|string',
            'assessor_id' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $assessment->update($validated);

        return $this->success($assessment, 'Assessment updated successfully.');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $assessment = CompetencyAssessment::findOrFail($id);
        $assessment->delete();

        return $this->success(null, 'Assessment deleted successfully.');
    }

    public function assessments(): \Illuminate\Http\JsonResponse
    {
        $assessments = CompetencyAssessment::with('driver')
            ->where('score', '<', 60)
            ->get();

        return $this->success($assessments, 'Skill gap assessments retrieved successfully.');
    }
}