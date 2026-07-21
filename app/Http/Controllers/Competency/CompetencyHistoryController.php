<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyHistory;
use Illuminate\Http\Request;

class CompetencyHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $recordType = $request->query('record_type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyHistory::with(['driver', 'competency'])->orderByDesc('recorded_at');

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
            'historical_records' => CompetencyHistory::count(),
            'archived' => CompetencyHistory::where('record_type', 'review')->count(),
            'timeline_events' => CompetencyHistory::count(),
            'skill_improvement' => '+3.2%',
        ];

        return view('admin.competency.competency-history', compact('histories', 'stats'));
    }
}
