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

        return view('admin.learning.learning-analytics', compact('assignments', 'assessments', 'stats', 'modules', 'drivers'));
    }
}
