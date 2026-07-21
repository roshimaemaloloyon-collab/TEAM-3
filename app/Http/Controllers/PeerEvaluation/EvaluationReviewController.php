<?php

namespace App\Http\Controllers\PeerEvaluation;

use App\Http\Controllers\Controller;
use App\Models\PeerEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class EvaluationReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = PeerEvaluation::with(['evaluator', 'evaluatedDriver', 'reviewer'])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('evaluator', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('evaluatedDriver', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $evaluations = $query->paginate($perPage)->withQueryString();

        $stats = [
            'pending_reviews' => PeerEvaluation::where('status', 'submitted')->count(),
            'approved' => PeerEvaluation::where('status', 'approved')->count(),
            'rejected' => PeerEvaluation::where('status', 'rejected')->count(),
        ];

        return view('admin.peer-evaluation.evaluation-review', compact('evaluations', 'stats'));
    }

    public function approve(Request $request, PeerEvaluation $peerEvaluation)
    {
        $validated = $request->validate([
            'admin_remarks' => 'nullable|string|max:2000',
        ]);

        $peerEvaluation->update([
            'status' => 'approved',
            'admin_remarks' => $validated['admin_remarks'],
            'reviewed_by' => auth()->id() ?? 1,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Evaluation approved successfully.');
    }

    public function reject(Request $request, PeerEvaluation $peerEvaluation)
    {
        $validated = $request->validate([
            'admin_remarks' => 'required|string|max:2000',
        ]);

        $peerEvaluation->update([
            'status' => 'rejected',
            'admin_remarks' => $validated['admin_remarks'],
            'reviewed_by' => auth()->id() ?? 1,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Evaluation rejected.');
    }
}
