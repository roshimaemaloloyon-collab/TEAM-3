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

        $query = PeerEvaluation::with(['evaluator', 'evaluatedDriver'])
            ->orderByDesc('evaluation_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('evaluator', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('evaluatedDriver', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $history = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total_historical' => PeerEvaluation::count(),
            'archived' => PeerEvaluation::where('status', 'rejected')->count(),
            'timeline_entries' => PeerEvaluation::count(),
            'historical_avg_score' => PeerEvaluation::whereNotNull('overall_score')->avg('overall_score'),
        ];

        return view('admin.peer-evaluation.evaluation-history', compact('history', 'stats'));
    }
}
