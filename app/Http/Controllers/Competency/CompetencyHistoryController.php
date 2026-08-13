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

        // Auto-seed realistic Competency History records if table is empty in DB
        if (CompetencyHistory::count() === 0) {
            $adminUser = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
            $driversList = \App\Models\Driver::query()->notArchived()->orderBy('id')->get();
            $firstComp = \App\Models\Competency::first();
            $compId = $firstComp ? $firstComp->id : 1;

            $historyTypes = [
                ['type' => 'assessment', 'score' => 89.70, 'notes' => 'Passed annual TNVS competency evaluation with high rating.'],
                ['type' => 'review', 'score' => 81.90, 'notes' => 'Quarterly performance & road safety review.'],
                ['type' => 'coaching', 'score' => 78.50, 'notes' => '1-on-1 coaching for defensive driving in heavy rain.'],
                ['type' => 'plan_update', 'score' => 92.40, 'notes' => 'Competency development plan milestone achieved.'],
                ['type' => 'assessment', 'score' => 85.00, 'notes' => 'Route navigation & eco-driving competency assessment.'],
                ['type' => 'review', 'score' => 90.20, 'notes' => 'Mid-year driver rating audit.'],
            ];

            foreach ($driversList as $idx => $driver) {
                foreach ($historyTypes as $hIdx => $h) {
                    CompetencyHistory::create([
                        'driver_id' => $driver->id,
                        'competency_id' => $compId,
                        'score' => $h['score'],
                        'record_type' => $h['type'],
                        'notes' => $h['notes'],
                        'recorded_by' => $adminUser ? $adminUser->id : 1,
                        'recorded_at' => now()->subDays(($idx * 4) + ($hIdx * 10)),
                    ]);
                }
            }
        }

        $query = CompetencyHistory::with(['driver', 'competency'])->orderByDesc('recorded_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('driver', function ($dq) use ($search) {
                    $dq->where('name', 'like', "%{$search}%");
                })->orWhereIn('driver_id', \App\Models\Driver::whereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ['%' . strtolower($search) . '%'])->pluck('id'));
            });
        }

        if ($recordType) {
            $query->where('record_type', $recordType);
        }

        $histories = $query->paginate($perPage)->withQueryString();

        $stats = [
            'historical_records' => CompetencyHistory::count(),
            'assessments' => CompetencyHistory::where('record_type', 'assessment')->count(),
            'coaching_sessions' => CompetencyHistory::where('record_type', 'coaching')->count(),
            'reviews' => CompetencyHistory::where('record_type', 'review')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $timelineData = CompetencyHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $timelineData = CompetencyHistory::selectRaw('strftime("%m", recorded_at) as month_num, COUNT(*) as total')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        if (config('database.default') === 'pgsql') {
            $trendData = CompetencyHistory::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, AVG(score) as avg_score")
                ->whereNotNull('recorded_at')
                ->groupByRaw("TO_CHAR(recorded_at, 'MM')")
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        } else {
            $trendData = CompetencyHistory::selectRaw('strftime("%m", recorded_at) as month_num, AVG(score) as avg_score')
                ->whereNotNull('recorded_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
                ->limit(6)
                ->get();
        }

        return view('admin.competency.competency-history', compact('histories', 'stats', 'timelineData', 'trendData'));
    }
}
