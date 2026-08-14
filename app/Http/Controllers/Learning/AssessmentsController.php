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

        $quizPerformance = LearningAssessment::with('module')
            ->selectRaw('learning_module_id, AVG(score) as avg_score')
            ->groupBy('learning_module_id')
            ->get();

        $passFailData = [
            'Passed' => LearningAssessment::where('status', 'passed')->count(),
            'Failed' => LearningAssessment::where('status', 'failed')->count(),
        ];

        return view('admin.learning.assessments', compact('assessments', 'stats', 'quizPerformance', 'passFailData'));
    }

    public function retake($id)
    {
        $assessment = LearningAssessment::findOrFail($id);
        if ($assessment->attempt < $assessment->max_attempts) {
            $assessment->increment('attempt');
            $assessment->status = 'pending';
            $assessment->save();
            return back()->with('success', 'Assessment attempt reset for ' . ($assessment->driver->name ?? 'Driver') . '. Status set to Pending.');
        }

        return back()->with('error', 'Maximum attempts reached for this assessment.');
    }

    public function update(Request $request, $id)
    {
        $assessment = LearningAssessment::findOrFail($id);
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'status' => 'required|string',
        ]);

        $assessment->score = $request->score;
        $assessment->status = $request->status;
        $assessment->save();

        return back()->with('success', 'Assessment result updated successfully.');
    }

    public function destroy($id)
    {
        $assessment = LearningAssessment::findOrFail($id);
        $assessment->delete();

        return back()->with('success', 'Assessment record archived/deleted successfully.');
    }
}
