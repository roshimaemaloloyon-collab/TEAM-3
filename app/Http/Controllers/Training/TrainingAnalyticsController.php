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

        $trainingCompletion = Training::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->get();

        $categoryDist = Training::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->get();

        if (config('database.default') === 'pgsql') {
            $completionTrend = Training::selectRaw("TO_CHAR(start_datetime, 'MM') as month_num, COUNT(*) as total");
        } else {
            $completionTrend = Training::selectRaw('strftime("%m", start_datetime) as month_num, COUNT(*) as total');
        }
            ->where('status', 'completed')
            ->whereNotNull('start_datetime')
            ->when($year, fn($q) => $q->whereYear('start_datetime', $year))
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get();

        if (config('database.default') === 'pgsql') {
            $attendanceTrend = Attendance::selectRaw("TO_CHAR(created_at, 'MM') as month_num, COUNT(*) as total")->where('status', 'present')
            ->whereNotNull('created_at')
            ->groupByRaw("TO_CHAR(created_at, 'MM')")
            ->orderBy('month_num')
            ->get();
        } else {
            $attendanceTrend = Attendance::selectRaw('strftime("%m", created_at) as month_num, COUNT(*) as total')->where('status', 'present')
            ->whereNotNull('created_at')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get();
        }

        $comparative = Training::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->get();

        return view('admin.training.analytics', compact(
            'trainings',
            'stats',
            'trainingCompletion',
            'categoryDist',
            'completionTrend',
            'attendanceTrend',
            'comparative'
        ));
    }
}
