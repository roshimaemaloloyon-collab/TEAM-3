<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingEvaluation;
use Illuminate\Http\Request;

class TrainingEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = TrainingEvaluation::with(['driver', 'training'])->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $evaluations = $query->paginate($perPage)->withQueryString();

        $stats = [
            'avg_score' => TrainingEvaluation::avg('overall_rating') ? number_format(TrainingEvaluation::avg('overall_rating'), 2) : '0.00',
            'satisfaction' => TrainingEvaluation::avg('overall_rating') ? number_format(TrainingEvaluation::avg('overall_rating'), 2) . '/5' : '0/5',
            'completed' => TrainingEvaluation::count(),
            'pending' => TrainingEvaluation::where('status', 'pending')->count(),
        ];

        $driver = config('database.default');
        if ($driver === 'pgsql') {
            $evaluationTrend = TrainingEvaluation::selectRaw("TO_CHAR(created_at, 'MM') as month_num, AVG(overall_rating) as avg_rating")
                ->whereNotNull('created_at')
                ->groupByRaw("TO_CHAR(created_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            if (config('database.default') === 'pgsql') {
            $evaluationTrend = TrainingEvaluation::selectRaw("TO_CHAR(created_at, 'MM') as month_num, AVG(overall_rating) as avg_rating")
                ->whereNotNull('created_at')
                ->groupByRaw("TO_CHAR(created_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $evaluationTrend = TrainingEvaluation::selectRaw('strftime("%m", created_at) as month_num, AVG(overall_rating) as avg_rating')
                ->whereNotNull('created_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }
        }

        $satisfactionByCategory = TrainingEvaluation::selectRaw('trainings.category, AVG(training_evaluations.overall_rating) as avg_rating')
            ->join('trainings', 'trainings.id', '=', 'training_evaluations.training_id')
            ->groupBy('trainings.category')
            ->get();

        return view('admin.training.evaluation', compact('evaluations', 'stats', 'evaluationTrend', 'satisfactionByCategory'));
    }
}
