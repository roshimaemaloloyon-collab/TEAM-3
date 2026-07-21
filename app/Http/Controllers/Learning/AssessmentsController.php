<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningAssessment;
use Illuminate\Http\Request;

class AssessmentsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = LearningAssessment::with(['driver', 'module'])->orderByDesc('completed_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $assessments = $query->paginate($perPage)->withQueryString();

        $stats = [
            'completed' => LearningAssessment::where('status', 'passed')->count(),
            'avg_score' => number_format(LearningAssessment::avg('score'), 2),
            'passed' => LearningAssessment::where('status', 'passed')->count(),
            'failed' => LearningAssessment::where('status', 'failed')->count(),
        ];

        return view('admin.learning.assessments', compact('assessments', 'stats'));
    }
}
