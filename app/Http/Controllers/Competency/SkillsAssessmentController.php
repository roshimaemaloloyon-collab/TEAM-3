<?php

namespace App\Http\Controllers\Competency;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\CompetencyAssessment;
use App\Models\User;
use Illuminate\Http\Request;

class SkillsAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $competencyId = $request->query('competency_id');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = CompetencyAssessment::with(['driver', 'competency'])->orderByDesc('assessed_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($competencyId) {
            $query->where('competency_id', $competencyId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $assessments = $query->paginate($perPage)->withQueryString();

        $stats = [
            'avg_score' => number_format(CompetencyAssessment::avg('score'), 2),
            'drivers_assessed' => CompetencyAssessment::distinct('driver_id')->count(),
            'pending' => CompetencyAssessment::where('status', 'pending')->count(),
            'completion_rate' => CompetencyAssessment::where('status', 'assessed')->count() . '%',
        ];

        $competencies = Competency::all();

        return view('admin.competency.skills-assessment', compact('assessments', 'stats', 'competencies'));
    }
}
