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

        return view('admin.training.evaluation', compact('evaluations', 'stats'));
    }
}
