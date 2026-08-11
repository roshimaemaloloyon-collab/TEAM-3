<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\PeerEvaluation;
use Illuminate\Http\Request;

class PeerEvaluationApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = PeerEvaluation::with('evaluator', 'evaluatedDriver');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('evaluator', function ($eq) use ($search) {
                    $eq->where('name', 'like', "%{$search}%");
                })->orWhereHas('evaluatedDriver', function ($ed) use ($search) {
                    $ed->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $evaluations = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($evaluations, 'Peer evaluations retrieved successfully.', 200, [
            'total' => $evaluations->total(),
            'current_page' => $evaluations->currentPage(),
            'last_page' => $evaluations->lastPage(),
            'per_page' => $evaluations->perPage(),
        ]);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $evaluation = PeerEvaluation::with('evaluator', 'evaluatedDriver', 'reviews')->find($id);

        if (! $evaluation) {
            return $this->error('Evaluation not found.', 404);
        }

        return $this->success($evaluation, 'Evaluation retrieved successfully.');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'evaluator_id' => 'required|integer',
            'evaluated_driver_id' => 'required|integer',
            'evaluation_date' => 'required|date',
            'is_anonymous' => 'nullable|boolean',
            'category_scores' => 'nullable|array',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'comments' => 'nullable|string',
            'suggestions' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $evaluation = PeerEvaluation::create($validated);

        return $this->success($evaluation, 'Peer evaluation created successfully.', 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $evaluation = PeerEvaluation::findOrFail($id);

        $validated = $request->validate([
            'evaluator_id' => 'nullable|integer',
            'evaluated_driver_id' => 'nullable|integer',
            'evaluation_date' => 'nullable|date',
            'is_anonymous' => 'nullable|boolean',
            'category_scores' => 'nullable|array',
            'overall_score' => 'nullable|numeric|min:0|max:100',
            'comments' => 'nullable|string',
            'suggestions' => 'nullable|string',
            'status' => 'nullable|string',
            'admin_remarks' => 'nullable|string',
            'reviewed_by' => 'nullable|integer',
            'reviewed_at' => 'nullable|date',
        ]);

        $evaluation->update($validated);

        return $this->success($evaluation, 'Evaluation updated successfully.');
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $evaluation = PeerEvaluation::findOrFail($id);
        $evaluation->delete();

        return $this->success(null, 'Evaluation deleted successfully.');
    }

    public function approve($id): \Illuminate\Http\JsonResponse
    {
        $evaluation = PeerEvaluation::findOrFail($id);
        $evaluation->update(['status' => 'approved']);

        return $this->success($evaluation, 'Evaluation approved successfully.');
    }

    public function reject($id): \Illuminate\Http\JsonResponse
    {
        $evaluation = PeerEvaluation::findOrFail($id);
        $evaluation->update(['status' => 'rejected']);

        return $this->success($evaluation, 'Evaluation rejected successfully.');
    }

    public function summary($driverId): \Illuminate\Http\JsonResponse
    {
        $evaluations = PeerEvaluation::where('evaluated_driver_id', $driverId)->get();

        $avgScore = $evaluations->avg('overall_score') ?? 0;
        $totalCount = $evaluations->count();
        $approvedCount = $evaluations->where('status', 'approved')->count();

        return $this->success([
            'driver_id' => $driverId,
            'total_evaluations' => $totalCount,
            'approved_evaluations' => $approvedCount,
            'average_score' => round($avgScore, 2),
        ], 'Evaluation summary retrieved successfully.');
    }
}