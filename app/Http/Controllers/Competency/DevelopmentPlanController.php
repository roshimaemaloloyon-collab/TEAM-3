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
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('plan_name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $plans = $query->paginate($perPage)->withQueryString();

        $stats = [
            'active' => CompetencyDevelopmentPlan::where('status', 'active')->count(),
            'completed' => CompetencyDevelopmentPlan::where('status', 'completed')->count(),
            'trainings' => CompetencyDevelopmentPlan::where('status', 'active')->count(),
            'learning_completed' => CompetencyDevelopmentPlan::where('status', 'completed')->count(),
        ];

        return view('admin.competency.development-plan', compact('plans', 'stats'));
    }
}
