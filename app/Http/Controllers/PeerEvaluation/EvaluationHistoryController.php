<?php

namespace App\Http\Controllers\PeerEvaluation;

use App\Http\Controllers\Controller;
use App\Models\EvaluationHistory;
use App\Models\PeerEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) ($request->query('per_page', 15));

        $query = EvaluationHistory::with(['peerEvaluation.evaluator', 'peerEvaluation.evaluatedDriver', 'performedBy'])
            ->orderByDesc('performed_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('peerEvaluation.evaluator', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('peerEvaluation.evaluatedDriver', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $history = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_historical' => PeerEvaluation::count(),
            'archived' => PeerEvaluation::where('status', 'archived')->count(),
            'timeline_entries' => EvaluationHistory::count(),
            'historical_avg_score' => PeerEvaluation::whereNotNull('overall_score')->avg('overall_score'),
        ];

        return view('admin.peer-evaluation.evaluation-history', compact('history', 'stats'));
    }
}
