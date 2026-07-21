<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CompetencyAssessment;
use App\Models\CompetencyDevelopmentPlan;
use App\Models\Kpi;
use App\Models\LearningAssignment;
use App\Models\LearningModule;
use App\Models\Notification;
use App\Models\PeerEvaluation;
use App\Models\Performance;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $driversQuery = User::where('role', 'driver');

        $totalDrivers = (clone $driversQuery)->count();
        $activeDrivers = (clone $driversQuery)->where('status', 'active')->count();
        $inactiveDrivers = (clone $driversQuery)->where('status', 'inactive')->count();
        $driversUnderReview = (clone $driversQuery)->where('status', 'under_review')->count();

        $avgPerformanceScore = Performance::avg('overall_score');
        $avgPerformanceScore = $avgPerformanceScore !== null ? number_format($avgPerformanceScore, 2) : '0.00';

        $pendingPeerEvaluations = PeerEvaluation::where('status', 'submitted')->count();
        $underReviewPeerEvaluations = PeerEvaluation::where('status', 'under_review')->count();
        $totalPendingPeerEvaluations = $pendingPeerEvaluations + $underReviewPeerEvaluations;

        $upcomingTrainings = Training::where('status', 'upcoming')->count();
        $ongoingTrainings = Training::where('status', 'ongoing')->count();
        $completedTrainings = Training::where('status', 'completed')->count();

        $assignedLearningModules = LearningAssignment::where('status', 'assigned')->count();
        $completedLearningModules = LearningAssignment::where('status', 'completed')->count();
        $learningCompletionRate = $assignedLearningModules > 0
            ? number_format(($completedLearningModules / $assignedLearningModules) * 100, 1)
            : '0.0';
        $certificatesEarned = Certificate::count();

        $avgCompetencyScore = CompetencyAssessment::avg('score');
        $avgCompetencyScore = $avgCompetencyScore !== null ? number_format($avgCompetencyScore, 2) : '0.00';

        $driversWithSkillGaps = CompetencyAssessment::where('score', '<', 60)
            ->distinct('driver_id')
            ->count();

        $mostImprovedCompetency = CompetencyAssessment::selectRaw('driver_id, MAX(score) as max_score')
            ->groupBy('driver_id')
            ->orderByDesc('max_score')
            ->first();

        $topPerformers = Performance::with('driver')
            ->orderByDesc('overall_score')
            ->limit(5)
            ->get()
            ->map(function ($perf) {
                $driver = $perf->driver;
                $competencyScore = CompetencyAssessment::where('driver_id', $perf->driver_id)->avg('score');
                return [
                    'driver' => $driver,
                    'performance_score' => $perf->overall_score ?? 0,
                    'competency_score' => $competencyScore !== null ? number_format($competencyScore, 2) : '0.00',
                    'safety_score' => $perf->safety_score ?? 0,
                    'overall_rating' => $perf->overall_score ?? 0,
                ];
            });

        $kpiStats = [
            'safety_score' => $this->kpiCategoryValue('safety'),
            'attendance_rate' => $this->kpiCategoryValue('attendance'),
            'trip_completion_rate' => $this->kpiCategoryValue('efficiency'),
            'customer_rating' => $this->kpiCategoryValue('customer_service'),
        ];

        $notifications = Notification::orderByDesc('created_at')->limit(5)->get();

        $performanceTrend = Performance::selectRaw('strftime("%b", recorded_at) as month, AVG(overall_score) as avg_score')
            ->whereNotNull('recorded_at')
            ->groupBy('month')
            ->orderByRaw('MIN(recorded_at)')
            ->limit(7)
            ->get();

        $overallPerformanceScore = Performance::avg('overall_score');
        $overallPerformanceScore = $overallPerformanceScore !== null ? number_format($overallPerformanceScore, 2) : '0.00';

        $totalKpis = Kpi::count();
        $achievedKpis = Kpi::where('status', 'achieved')->count();
        $kpiAchievementRate = $totalKpis > 0 ? number_format(($achievedKpis / $totalKpis) * 100, 1) : '0.0';

        $leadershipCandidates = CompetencyDevelopmentPlan::where('status', 'active')->distinct('driver_id')->count();
        $promotionReadyDrivers = CompetencyDevelopmentPlan::where('status', 'completed')->distinct('driver_id')->count();
        $activeDevelopmentPlans = CompetencyDevelopmentPlan::where('status', 'active')->count();

        return view('admin.dashboard', compact(
            'totalDrivers',
            'activeDrivers',
            'inactiveDrivers',
            'driversUnderReview',
            'avgPerformanceScore',
            'totalPendingPeerEvaluations',
            'upcomingTrainings',
            'ongoingTrainings',
            'completedTrainings',
            'assignedLearningModules',
            'completedLearningModules',
            'learningCompletionRate',
            'certificatesEarned',
            'avgCompetencyScore',
            'driversWithSkillGaps',
            'mostImprovedCompetency',
            'topPerformers',
            'kpiStats',
            'notifications',
            'performanceTrend',
            'overallPerformanceScore',
            'kpiAchievementRate',
            'leadershipCandidates',
            'promotionReadyDrivers',
            'activeDevelopmentPlans'
        ));
    }

    private function kpiCategoryValue(string $category): string
    {
        $value = Kpi::where('kpi_category', $category)->avg('achievement_percentage');
        return $value !== null ? number_format($value, 1) . '%' : '0%';
    }
}
