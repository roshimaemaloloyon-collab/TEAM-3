<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Training::where('status', 'completed')->orderByDesc('start_datetime');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $history = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_trainings' => Training::where('status', 'completed')->count(),
            'archived_sessions' => Training::where('status', 'completed')->count(),
            'timeline_events' => Training::count(),
            'completed_programs' => Training::where('status', 'completed')->count(),
        ];

        return view('admin.training.history', compact('history', 'stats'));
    }
}
