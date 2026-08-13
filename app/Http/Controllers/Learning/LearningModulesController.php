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
        $position = $request->query('position');
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

        if ($position) {
            $query->where('metadata->target_position', $position);
        }

        $modules = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_modules' => LearningModule::count(),
            'assigned_courses' => LearningAssignment::where('status', 'assigned')->count(),
            'active_modules' => LearningModule::where('status', 'active')->count(),
            'completed_courses' => LearningAssignment::where('status', 'completed')->count(),
        ];

        $driver = config('database.default');
        if ($driver === 'pgsql') {
            $progressData = LearningAssignment::selectRaw("TO_CHAR(assigned_date, 'MM') as month_num, AVG(progress_percentage) as avg_progress")
                ->whereNotNull('assigned_date')
                ->groupByRaw("TO_CHAR(assigned_date, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $progressData = LearningAssignment::selectRaw('strftime("%m", assigned_date) as month_num, AVG(progress_percentage) as avg_progress')
                ->whereNotNull('assigned_date')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }

        $completionData = LearningModule::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->get();

        $allModulesWithCounts = LearningModule::withCount('assignments')->get();
        $allDrivers = \App\Models\Driver::query()->notArchived()->orderBy('first_name')->get();

        return view('admin.learning.learning-modules', compact(
            'modules',
            'stats',
            'progressData',
            'completionData',
            'allModulesWithCounts',
            'allDrivers'
        ));
    }
}
