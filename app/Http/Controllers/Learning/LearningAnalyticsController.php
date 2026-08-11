<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningAssignment;
use App\Models\LearningAssessment;
use Illuminate\Http\Request;

class LearningAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $driver = $request->query('driver');
        $module = $request->query('module');
        $year = $request->query('year', date('Y'));

        $assignmentsQuery = LearningAssignment::with(['driver', 'module']);
        $assessmentsQuery = LearningAssessment::with(['driver', 'module']);

        if ($driver) {
            $assignmentsQuery->where('driver_id', $driver);
            $assessmentsQuery->where('driver_id', $driver);
        }

        if ($module) {
            $assignmentsQuery->where('learning_module_id', $module);
            $assessmentsQuery->where('learning_module_id', $module);
        }

        if ($year) {
            $assignmentsQuery->whereYear('assigned_date', $year);
            $assessmentsQuery->whereYear('completed_at', $year);
        }

        $assignments = $assignmentsQuery->get();
        $assessments = $assessmentsQuery->get();

        $stats = [
            'completion_rate' => $assignments->where('status', 'completed')->count() . '%',
            'avg_quiz_score' => $assessments->avg('score') ? number_format($assessments->avg('score'), 2) : '0.00',
            'learning_progress' => $assignments->avg('progress_percentage') ? number_format($assignments->avg('progress_percentage'), 2) . '%' : '0%',
            'module_effectiveness' => '92%',
        ];

        $modules = \App\Models\LearningModule::all();
        $drivers = \App\Models\User::where('role', 'driver')->get();

        $progressAnalytics = LearningAssignment::with('module')
            ->selectRaw('learning_module_id, AVG(progress_percentage) as avg_progress')
            ->groupBy('learning_module_id')
            ->get();

        $moduleDist = LearningAssignment::with('module')
            ->selectRaw('learning_module_id, COUNT(*) as total')
            ->groupBy('learning_module_id')
            ->get();

        if (config('database.default') === 'pgsql') {
            $completionTrend = LearningAssignment::selectRaw("TO_CHAR(assigned_date, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('assigned_date')
                ->when($year, fn($q) => $q->whereYear('assigned_date', $year))
                ->groupByRaw("TO_CHAR(assigned_date, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();

            $effectiveness = LearningAssignment::selectRaw("TO_CHAR(assigned_date, 'MM') as month_num, AVG(progress_percentage) as avg_progress")
                ->whereNotNull('assigned_date')
                ->when($year, fn($q) => $q->whereYear('assigned_date', $year))
                ->groupByRaw("TO_CHAR(assigned_date, 'MM')")
                ->orderBy('month_num')
                ->get();
        } else {
            $completionTrend = LearningAssignment::selectRaw('strftime("%m", assigned_date) as month_num, COUNT(*) as total')
                ->whereNotNull('assigned_date')
                ->when($year, fn($q) => $q->whereYear('assigned_date', $year))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();

            $effectiveness = LearningAssignment::selectRaw('strftime("%m", assigned_date) as month_num, AVG(progress_percentage) as avg_progress')
                ->whereNotNull('assigned_date')
                ->when($year, fn($q) => $q->whereYear('assigned_date', $year))
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->get();
        }

        $comparative = $assignments->groupBy('driver_id')->map(fn($items) => [
            'completion' => $items->avg('progress_percentage') ?? 0,
            'quiz' => $assessments->where('driver_id', $items->first()->driver_id ?? 0)->avg('score') ?? 0,
        ]);

        return view('admin.learning.learning-analytics', compact(
            'assignments',
            'assessments',
            'stats',
            'modules',
            'drivers',
            'progressAnalytics',
            'moduleDist',
            'completionTrend',
            'effectiveness',
            'comparative'
        ));
    }
}
