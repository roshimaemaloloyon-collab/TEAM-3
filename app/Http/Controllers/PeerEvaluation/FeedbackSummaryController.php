<?php

namespace App\Http\Controllers\PeerEvaluation;

use App\Http\Controllers\Controller;
use App\Models\EvaluationFeedback;
use App\Models\PeerEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackSummaryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) ($request->query('per_page', 15));

        $query = PeerEvaluation::query()
            ->select('evaluated_driver_id')
            ->selectRaw('COUNT(*) as total_evaluations')
            ->selectRaw('AVG(overall_score) as average_rating')
            ->groupBy('evaluated_driver_id')
            ->orderByDesc('average_rating');

        if ($search) {
            $query->whereHas('evaluatedDriver', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $summaries = $query->paginate($perPage)->withQueryString();

        $stats = [
            'positive_feedback' => PeerEvaluation::where('overall_score', '>=', 4)->count(),
            'improvement_opportunities' => PeerEvaluation::where('overall_score', '<', 3.5)->count(),
            'average_peer_rating' => PeerEvaluation::whereNotNull('overall_score')->avg('overall_score'),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.peer-evaluation.feedback-summary', compact('summaries', 'stats', 'drivers'));
    }

    public function show($driverId)
    {
        $driver = User::findOrFail($driverId);
        $evaluations = PeerEvaluation::where('evaluated_driver_id', $driverId)
            ->with('evaluator')
            ->orderByDesc('evaluation_date')
            ->paginate(10);

        $feedback = EvaluationFeedback::where('driver_id', $driverId)->first();

        return view('admin.peer-evaluation.feedback-detail', compact('driver', 'evaluations', 'feedback'));
    }
}
