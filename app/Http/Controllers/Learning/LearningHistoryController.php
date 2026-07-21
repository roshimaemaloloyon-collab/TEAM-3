<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningHistory;
use Illuminate\Http\Request;

class LearningHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = LearningHistory::with(['driver', 'module'])->orderByDesc('recorded_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($recordType) {
            $query->where('record_type', $recordType);
        }

        $histories = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_records' => LearningHistory::count(),
            'completed_courses' => LearningHistory::where('record_type', 'completion')->count(),
            'certificates_earned' => LearningHistory::where('record_type', 'certificate')->count(),
            'timeline_events' => LearningHistory::count(),
        ];

        return view('admin.learning.learning-history', compact('histories', 'stats'));
    }
}
