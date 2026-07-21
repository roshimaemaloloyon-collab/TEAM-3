<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Attendance;
use App\Models\TrainingEvaluation;
use Illuminate\Http\Request;

class TrainingAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $trainer = $request->query('trainer');
        $year = $request->query('year', date('Y'));

        $query = Training::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($trainer) {
            $query->where('instructor', $trainer);
        }

        if ($year) {
            $query->whereYear('start_datetime', $year);
        }

        $trainings = $query->get();

        $stats = [
            'completion_rate' => Training::where('status', 'completed')->count() . '%',
            'avg_attendance' => Attendance::count() > 0 ? number_format((Attendance::where('status', 'present')->count() / Attendance::count()) * 100, 1) . '%' : '0%',
            'avg_evaluation' => TrainingEvaluation::avg('overall_rating') ? number_format(TrainingEvaluation::avg('overall_rating'), 2) . '/5' : '0/5',
            'effectiveness' => '92%',
        ];

        return view('admin.training.analytics', compact('trainings', 'stats'));
    }
}
