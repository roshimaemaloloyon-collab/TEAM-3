<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Performance;
use App\Models\PeerEvaluation;
use App\Models\Training;
use App\Models\TrainingRegistration;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\CompetencyAssessment;
use App\Models\Notification;
use Illuminate\Http\Request;

class DriverDashboardController extends Controller
{
    protected function driver(Request $request)
    {
        $driver = null;

        if (auth()->check()) {
            $driver = Driver::where('user_id', auth()->id())->first();

            if (!$driver) {
                $driver = Driver::where('contact_number', auth()->user()->phone)->first();
            }

            if (!$driver) {
                $driver = Driver::where('email', auth()->user()->email)->first();
            }
        }

        if (!$driver) {
            $driver = Driver::first();
        }

        return $driver;
    }

    public function dashboard(Request $request)
    {
        $driver = $this->driver($request);

        $totalDrivers = Driver::notArchived()->count();
        $activeDrivers = Driver::where('status', 'active')->count();
        $avgPerformance = Driver::notArchived()->avg('performance_score') ?? 4.6;

        $myPerformance = Performance::where('driver_id', $driver->id ?? 0)->latest()->first();
        $myOverallScore = $myPerformance ? $myPerformance->overall_score : 0;
        $myRating = $myPerformance ? $myPerformance->getOverallRating() : 'N/A';

        $myTrainings = TrainingRegistration::where('driver_id', $driver->id ?? 0)->count();
        $myCompletedTrainings = TrainingRegistration::where('driver_id', $driver->id ?? 0)->where('status', 'approved')->count();
        $myAttendanceRate = $myTrainings > 0 ? round(($myCompletedTrainings / $myTrainings) * 100, 1) : 0;

        $myCertificates = Certificate::where('driver_id', $driver->id ?? 0)->where('status', 'issued')->count();
        $myEvaluations = PeerEvaluation::where('evaluated_driver_id', $driver->id ?? 0)->where('status', 'approved')->count();
        $myAvgScore = PeerEvaluation::where('evaluated_driver_id', $driver->id ?? 0)->where('status', 'approved')->avg('overall_score') ?? 0;

        $myCompetency = CompetencyAssessment::where('driver_id', $driver->id ?? 0)->avg('score') ?? 0;
        $mySkillGaps = CompetencyAssessment::where('driver_id', $driver->id ?? 0)->where('score', '<', 60)->count();

        $competencyScores = CompetencyAssessment::with('competency')
            ->where('driver_id', $driver->id ?? 0)
            ->orderByDesc('score')
            ->limit(5)
            ->get();

        $upcomingTrainings = Training::where('status', 'upcoming')->count();
        $ongoingTrainings = Training::where('status', 'ongoing')->count();
        $completedTrainings = Training::where('status', 'completed')->count();

        $notifications = Notification::orderByDesc('created_at')->limit(5)->get();

        if (config('database.default') === 'pgsql') {
            $performanceTrend = Performance::selectRaw("TO_CHAR(recorded_at, 'MM') as month_num, AVG(overall_score) as avg_score")
                            ->whereNotNull('recorded_at')
            ->groupBy('month')
            ->orderByRaw('MIN(recorded_at)')
            ->limit(7)
            ->get();
        } else {
            $performanceTrend = Performance::selectRaw('strftime("%m", recorded_at) as month_num, AVG(overall_score) as avg_score')
                            ->whereNotNull('recorded_at')
            ->groupBy('month')
            ->orderByRaw('MIN(recorded_at)')
            ->limit(7)
            ->get();
        }

        $topPerformers = Performance::with('driver')
            ->orderByDesc('overall_score')
            ->limit(5)
            ->get()
            ->map(function ($perf) {
                return [
                    'driver' => $perf->driver,
                    'performance_score' => $perf->overall_score ?? 0,
                    'rating' => $perf->getOverallRating(),
                ];
            });

        $kpiStats = [
            'safety_score' => Performance::avg('safety_score') ?? 0,
            'attendance_rate' => Performance::avg('attendance_rate') ?? 0,
            'trip_completion_rate' => Performance::avg('trip_completion_rate') ?? 0,
            'customer_rating' => Performance::avg('customer_rating') ?? 0,
        ];

        $recentPerformances = Performance::where('driver_id', $driver->id ?? 0)
            ->orderByDesc('recorded_at')
            ->limit(3)
            ->get();

        $upcomingTrainingList = Training::where('status', 'upcoming')
            ->orderByDesc('start_datetime')
            ->limit(3)
            ->get();

        $recentEvaluations = PeerEvaluation::where('evaluated_driver_id', $driver->id ?? 0)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('driver.dashboard', compact(
            'driver',
            'totalDrivers',
            'activeDrivers',
            'avgPerformance',
            'myPerformance',
            'myOverallScore',
            'myRating',
            'myTrainings',
            'myCompletedTrainings',
            'myAttendanceRate',
            'myCertificates',
            'myEvaluations',
            'myAvgScore',
            'myCompetency',
            'mySkillGaps',
            'upcomingTrainings',
            'ongoingTrainings',
            'completedTrainings',
            'notifications',
            'performanceTrend',
            'topPerformers',
            'kpiStats',
            'recentPerformances',
            'upcomingTrainingList',
            'recentEvaluations',
            'competencyScores'
        ));
    }

    public function performance(Request $request)
    {
        $driver = $this->driver($request);

        $performances = Performance::where('driver_id', $driver->id ?? 0)
            ->orderByDesc('recorded_at')
            ->paginate(15);

        $myPerformance = Performance::where('driver_id', $driver->id ?? 0)->latest()->first();
        $myOverallScore = $myPerformance ? $myPerformance->overall_score : 0;

        $kpiStats = [
            'safety_score' => Performance::where('driver_id', $driver->id ?? 0)->avg('safety_score') ?? 0,
            'attendance_rate' => Performance::where('driver_id', $driver->id ?? 0)->avg('attendance_rate') ?? 0,
            'trip_completion_rate' => Performance::where('driver_id', $driver->id ?? 0)->avg('trip_completion_rate') ?? 0,
            'customer_rating' => Performance::where('driver_id', $driver->id ?? 0)->avg('customer_rating') ?? 0,
        ];

        return view('driver.pages.performance', compact(
            'driver',
            'performances',
            'myOverallScore',
            'kpiStats'
        ));
    }

    public function competencies(Request $request)
    {
        $driver = $this->driver($request);

        $assessments = CompetencyAssessment::where('driver_id', $driver->id ?? 0)
            ->orderByDesc('created_at')
            ->paginate(15);

        $avgScore = CompetencyAssessment::where('driver_id', $driver->id ?? 0)->avg('score') ?? 0;
        $skillGaps = CompetencyAssessment::where('driver_id', $driver->id ?? 0)->where('score', '<', 60)->count();

        return view('driver.pages.competencies', compact(
            'driver',
            'assessments',
            'avgScore',
            'skillGaps'
        ));
    }

    public function learning(Request $request)
    {
        $driver = $this->driver($request);

        $modules = Training::where('type', 'learning')
            ->orderByDesc('created_at')
            ->paginate(15);

        $certificates = Certificate::where('driver_id', $driver->id ?? 0)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('driver.pages.learning', compact(
            'driver',
            'modules',
            'certificates'
        ));
    }

    public function trainings(Request $request)
    {
        $driver = $this->driver($request);

        $trainings = TrainingRegistration::where('driver_id', $driver->id ?? 0)
            ->orderByDesc('created_at')
            ->paginate(15);

        $myTrainings = TrainingRegistration::where('driver_id', $driver->id ?? 0)->count();
        $myCompletedTrainings = TrainingRegistration::where('driver_id', $driver->id ?? 0)->where('status', 'approved')->count();
        $myAttendanceRate = $myTrainings > 0 ? round(($myCompletedTrainings / $myTrainings) * 100, 1) : 0;

        return view('driver.pages.trainings', compact(
            'driver',
            'trainings',
            'myTrainings',
            'myCompletedTrainings',
            'myAttendanceRate'
        ));
    }

    public function career(Request $request)
    {
        $driver = $this->driver($request);

        return view('driver.pages.career', compact('driver'));
    }

    public function recognition(Request $request)
    {
        $driver = $this->driver($request);

        return view('driver.pages.recognition', compact('driver'));
    }

    public function evaluations(Request $request)
    {
        $driver = $this->driver($request);

        $evaluations = PeerEvaluation::where('evaluated_driver_id', $driver->id ?? 0)
            ->orderByDesc('created_at')
            ->paginate(15);

        $myEvaluations = PeerEvaluation::where('evaluated_driver_id', $driver->id ?? 0)->where('status', 'approved')->count();
        $myAvgScore = PeerEvaluation::where('evaluated_driver_id', $driver->id ?? 0)->where('status', 'approved')->avg('overall_score') ?? 0;

        return view('driver.pages.evaluations', compact(
            'driver',
            'evaluations',
            'myEvaluations',
            'myAvgScore'
        ));
    }

    public function reports(Request $request)
    {
        $driver = $this->driver($request);

        return view('driver.pages.reports', compact('driver'));
    }

    public function notifications(Request $request)
    {
        $driver = $this->driver($request);

        $notifications = Notification::orderByDesc('created_at')->paginate(15);

        return view('driver.pages.notifications', compact(
            'driver',
            'notifications'
        ));
    }

    public function settings(Request $request)
    {
        $driver = $this->driver($request);

        return view('driver.pages.settings', compact('driver'));
    }
}
