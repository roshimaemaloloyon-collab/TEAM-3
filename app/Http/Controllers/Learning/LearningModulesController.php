<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
use App\Models\LearningAssignment;
use Illuminate\Http\Request;

class LearningModulesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = LearningModule::with('creator')->orderByDesc('created_at');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $modules = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_modules' => LearningModule::count(),
            'assigned_courses' => LearningAssignment::where('status', 'assigned')->count(),
            'active_modules' => LearningModule::where('status', 'active')->count(),
            'completed_courses' => LearningAssignment::where('status', 'completed')->count(),
        ];

        return view('admin.learning.learning-modules', compact('modules', 'stats'));
    }
}
