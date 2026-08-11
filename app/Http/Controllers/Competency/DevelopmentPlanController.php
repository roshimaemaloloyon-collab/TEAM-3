<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\CompetencyDevelopmentPlan;
use Illuminate\Http\Request;

class DevelopmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyDevelopmentPlan::with('driver')->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('driver', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhere('plan_name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $plans = $query->paginate($perPage)->withQueryString();

        $stats = [
            'active' => CompetencyDevelopmentPlan::where('status', 'active')->count(),
            'completed' => CompetencyDevelopmentPlan::where('status', 'completed')->count(),
            'on_hold' => CompetencyDevelopmentPlan::where('status', 'on_hold')->count(),
            'avg_progress' => number_format(CompetencyDevelopmentPlan::avg('completion_percentage'), 1) . '%',
        ];

        if (config('database.default') === 'pgsql') {
            $progressData = CompetencyDevelopmentPlan::selectRaw("TO_CHAR(created_at, 'MM') as month_num, AVG(progress_percentage) as avg_progress")
                ->whereNotNull('created_at')
                ->groupByRaw("TO_CHAR(created_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $progressData = CompetencyDevelopmentPlan::selectRaw('strftime("%m", created_at) as month_num, AVG(progress_percentage) as avg_progress')
                ->whereNotNull('created_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        $trainingData = CompetencyDevelopmentPlan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.competency.development-plan', compact('plans', 'stats', 'progressData', 'trainingData'));
    }
}
