<?php

namespace App\Http\Controllers\PeerEvaluation;

use App\Http\Controllers\Controller;
use App\Models\PeerEvaluation;
use App\Models\User;
use Illuminate\Http\Request;

class DriverEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = PeerEvaluation::with(['evaluator', 'evaluatedDriver'])
            ->orderByDesc('evaluation_date');

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
            'total' => PeerEvaluation::count(),
            'pending' => PeerEvaluation::where('status', 'submitted')->count(),
            'completed' => PeerEvaluation::whereIn('status', ['approved'])->count(),
            'avg_rating' => PeerEvaluation::whereNotNull('overall_score')->avg('overall_score'),
        ];

        $drivers = User::where('role', 'driver')->get(['id', 'name']);

        return view('admin.peer-evaluation.driver-evaluation', compact('evaluations', 'stats', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'evaluated_driver_id' => 'required|exists:users,id',
            'evaluation_date' => 'required|date',
            'is_anonymous' => 'sometimes|boolean',
            'category_scores' => 'nullable|array',
            'overall_score' => 'nullable|numeric|min:1|max:5',
            'comments' => 'nullable|string|max:2000',
            'suggestions' => 'nullable|string|max:2000',
        ]);

        $validated['evaluator_id'] = auth()->id() ?? 1;
        $validated['is_anonymous'] = $request->boolean('is_anonymous', false);
        $validated['status'] = 'submitted';

        PeerEvaluation::create($validated);

        return back()->with('success', 'Evaluation submitted successfully.');
    }

    public function show(PeerEvaluation $peerEvaluation)
    {
        $peerEvaluation->load(['evaluator', 'evaluatedDriver', 'reviews.reviewer']);
        return view('admin.peer-evaluation.partials.view-evaluation', compact('peerEvaluation'));
    }

    public function edit(PeerEvaluation $peerEvaluation)
    {
        $peerEvaluation->load(['evaluator', 'evaluatedDriver']);
        $drivers = User::where('role', 'driver')->get(['id', 'name']);
        return view('admin.peer-evaluation.partials.edit-evaluation', compact('peerEvaluation', 'drivers'));
    }

    public function update(Request $request, PeerEvaluation $peerEvaluation)
    {
        $validated = $request->validate([
            'evaluated_driver_id' => 'required|exists:users,id',
            'evaluation_date' => 'required|date',
            'is_anonymous' => 'sometimes|boolean',
            'category_scores' => 'nullable|array',
            'overall_score' => 'nullable|numeric|min:1|max:5',
            'comments' => 'nullable|string|max:2000',
            'suggestions' => 'nullable|string|max:2000',
        ]);

        $validated['is_anonymous'] = $request->boolean('is_anonymous', false);

        $peerEvaluation->update($validated);

        return back()->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(PeerEvaluation $peerEvaluation)
    {
        $peerEvaluation->delete();

        return back()->with('success', 'Evaluation deleted successfully.');
    }
}
