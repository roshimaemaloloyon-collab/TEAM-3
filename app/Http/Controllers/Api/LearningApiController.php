<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\LearningModule;
use App\Models\LearningAssessment;
use App\Models\Certificate;
use Illuminate\Http\Request;

class LearningApiController extends ApiController
{
    public function modules(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = LearningModule::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 15);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $modules = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return $this->success($modules, 'Learning modules retrieved successfully.', 200, [
            'total' => $modules->total(),
            'current_page' => $modules->currentPage(),
            'last_page' => $modules->lastPage(),
            'per_page' => $modules->perPage(),
        ]);
    }

    public function showModule($id): \Illuminate\Http\JsonResponse
    {
        $module = LearningModule::find($id);

        if (! $module) {
            return $this->error('Module not found.', 404);
        }

        return $this->success($module, 'Module retrieved successfully.');
    }

    public function storeModule(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'duration' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);

        $module = LearningModule::create($validated);

        return $this->success($module, 'Module created successfully.', 201);
    }

    public function updateModule(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $module = LearningModule::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'duration' => 'nullable|integer',
            'status' => 'nullable|string',
        ]);

        $module->update($validated);

        return $this->success($module, 'Module updated successfully.');
    }

    public function destroyModule($id): \Illuminate\Http\JsonResponse
    {
        $module = LearningModule::findOrFail($id);
        $module->delete();

        return $this->success(null, 'Module deleted successfully.');
    }

    public function assessments(): \Illuminate\Http\JsonResponse
    {
        $assessments = LearningAssessment::with('driver', 'module')
            ->latest()
            ->paginate(15);

        return $this->success($assessments, 'Learning assessments retrieved successfully.');
    }

    public function certificates(): \Illuminate\Http\JsonResponse
    {
        $certificates = Certificate::with('driver', 'training')
            ->latest()
            ->paginate(15);

        return $this->success($certificates, 'Certificates retrieved successfully.');
    }
}