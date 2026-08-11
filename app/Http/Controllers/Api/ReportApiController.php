<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportApiController extends ApiController
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = Report::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $reports = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($reports, 'Reports retrieved successfully.', 200, [
            'total' => $reports->total(),
            'current_page' => $reports->currentPage(),
            'last_page' => $reports->lastPage(),
            'per_page' => $reports->perPage(),
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'data' => 'nullable|array',
            'format' => 'nullable|string',
        ]);

        $report = Report::create($validated);

        return $this->success($report, 'Report created successfully.', 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $report = Report::find($id);

        if (! $report) {
            return $this->error('Report not found.', 404);
        }

        return $this->success($report, 'Report retrieved successfully.');
    }

    public function export($id): \Illuminate\Http\JsonResponse
    {
        $report = Report::findOrFail($id);

        return $this->success([
            'report' => $report,
            'export_url' => route('api.reports.export', $id),
        ], 'Report export ready.');
    }
}