<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Notifications\TrainingNotificationsController;
use App\Http\Controllers\Notifications\PerformanceNotificationsController;
use App\Http\Controllers\Notifications\SystemAnnouncementsController;
use App\Http\Controllers\Notifications\NotificationHistoryController;
use App\Http\Controllers\Notifications\NotificationSettingsController;
use App\Http\Controllers\Notifications\NotificationLogsController;
use App\Http\Controllers\UserManagement\UserAccountsController;
use App\Http\Controllers\UserManagement\UserRolesPermissionsController;
use App\Http\Controllers\UserManagement\AccountManagementController;
use App\Http\Controllers\UserManagement\LoginActivityLogsController;
use App\Http\Controllers\UserManagement\SecurityMonitoringController;
use App\Http\Controllers\UserManagement\AuditLogsController;
use App\Http\Controllers\PeerEvaluation\DriverEvaluationController;
use App\Http\Controllers\PeerEvaluation\EvaluationReviewController;
use App\Http\Controllers\PeerEvaluation\FeedbackSummaryController;
use App\Http\Controllers\PeerEvaluation\EvaluationReportController;
use App\Http\Controllers\PeerEvaluation\EvaluationAnalyticsController;
use App\Http\Controllers\PeerEvaluation\EvaluationHistoryController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\Api\TrainingApiController;
use App\Http\Controllers\SuccessionController;
use App\Http\Controllers\RecognitionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/driver-dashboard', function () {
    return view('dashboard');
})->name('driver.dashboard');

// ─────────────────────────────────────────────────────────────
// Super Admin Secret Login Route (Option A — URL-only access)
// Access URL: /login
// NOTE: Authentication logic will be wired up per team's system.
// ─────────────────────────────────────────────────────────────
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Placeholder POST route — teams will replace this with their own auth logic
Route::post('/login', function () {
    // TODO: Wire up your team's authentication logic here.
    // Example using Laravel Auth:
    //   $credentials = request()->validate(['email' => 'required|email', 'password' => 'required']);
    //   if (Auth::attempt($credentials, request()->boolean('remember'))) {
    //       return redirect()->intended('/dashboard');
    //   }
    //   return back()->withErrors(['email' => 'Invalid credentials.']);
    return back();
})->name('login.post');

// Dashboard Route — protect with your team's middleware when ready
Route::get('/dashboard', function () {
    // TODO: Add auth middleware once your login system is set up.
    // ->middleware('auth')
    return view('dashboard');
})->name('dashboard');

// Admin Routes
Route::get('/admin', App\Http\Controllers\AdminDashboardController::class)->name('admin.dashboard');

Route::get('/admin/drivers/profile/{id}', function ($id) {
    return view('admin.driver-profile');
})->name('admin.drivers.profile');

Route::get('/admin/drivers', function () {
    return view('admin.drivers');
})->name('admin.drivers.index');

Route::middleware('web')->prefix('admin/performance')->name('admin.performance.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.performance.drivers');
    })->name('index');

    Route::get('/drivers', [App\Http\Controllers\Performance\DriverPerformanceController::class, 'index'])->name('drivers');
    Route::get('/kpi', [App\Http\Controllers\Performance\KpiMonitoringController::class, 'index'])->name('kpi');
    Route::get('/reviews', [App\Http\Controllers\Performance\PerformanceReviewsController::class, 'index'])->name('reviews');
    Route::get('/reports', [App\Http\Controllers\Performance\PerformanceReportsController::class, 'index'])->name('reports');
    Route::get('/analytics', [App\Http\Controllers\Performance\PerformanceAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/history', [App\Http\Controllers\Performance\PerformanceHistoryController::class, 'index'])->name('history');
});

Route::middleware('web')->prefix('admin/competency')->name('admin.competency.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.competency.assessments');
    })->name('index');

    Route::get('/assessments', [App\Http\Controllers\Competency\SkillsAssessmentController::class, 'index'])->name('assessments');
    Route::get('/results', [App\Http\Controllers\Competency\AssessmentResultsController::class, 'index'])->name('results');
    Route::get('/plans', [App\Http\Controllers\Competency\DevelopmentPlanController::class, 'index'])->name('plans');
    Route::get('/reports', [App\Http\Controllers\Competency\CompetencyReportsController::class, 'index'])->name('reports');
    Route::get('/analytics', [App\Http\Controllers\Competency\CompetencyAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/history', [App\Http\Controllers\Competency\CompetencyHistoryController::class, 'index'])->name('history');
});

Route::middleware('web')->prefix('admin/learning')->name('admin.learning.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.learning.modules');
    })->name('index');

    Route::get('/modules', [App\Http\Controllers\Learning\LearningModulesController::class, 'index'])->name('modules');
    Route::get('/assessments', [App\Http\Controllers\Learning\AssessmentsController::class, 'index'])->name('assessments');
    Route::get('/certificates', [App\Http\Controllers\Learning\CertificatesController::class, 'index'])->name('certificates');
    Route::get('/reports', [App\Http\Controllers\Learning\LearningReportsController::class, 'index'])->name('reports');
    Route::get('/analytics', [App\Http\Controllers\Learning\LearningAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/history', [App\Http\Controllers\Learning\LearningHistoryController::class, 'index'])->name('history');
});

Route::middleware('web')->prefix('admin/training')->name('admin.training.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.training.schedule');
    })->name('index');

    Route::get('/schedule', [App\Http\Controllers\Training\TrainingScheduleController::class, 'index'])->name('schedule');
    Route::get('/attendance', [App\Http\Controllers\Training\TrainingAttendanceController::class, 'index'])->name('attendance');
    Route::get('/evaluations', [App\Http\Controllers\Training\TrainingEvaluationController::class, 'index'])->name('evaluations');
    Route::get('/reports', [App\Http\Controllers\Training\TrainingReportsController::class, 'index'])->name('reports');
    Route::get('/analytics', [App\Http\Controllers\Training\TrainingAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/history', [App\Http\Controllers\Training\TrainingHistoryController::class, 'index'])->name('history');
});

Route::get('/admin/succession/leadership', [SuccessionController::class, 'leadership'])->name('admin.succession.leadership');
Route::get('/admin/succession/career-path', [SuccessionController::class, 'careerPath'])->name('admin.succession.career-path');
Route::get('/admin/succession/development-plan', [SuccessionController::class, 'developmentPlan'])->name('admin.succession.development-plan');
Route::get('/admin/succession/promotion-readiness', [SuccessionController::class, 'promotionReadiness'])->name('admin.succession.promotion-readiness');
Route::get('/admin/succession/succession-history', [SuccessionController::class, 'successionHistory'])->name('admin.succession.succession-history');
Route::get('/admin/succession/talent-pool', [SuccessionController::class, 'talentPool'])->name('admin.succession.talent-pool');

Route::get('/admin/recognition/awards', [RecognitionController::class, 'awards'])->name('admin.recognition.awards');
Route::get('/admin/recognition/badges', [RecognitionController::class, 'badges'])->name('admin.recognition.badges');
Route::get('/admin/recognition/leaderboard', [RecognitionController::class, 'leaderboard'])->name('admin.recognition.leaderboard');
Route::get('/admin/recognition/history', [RecognitionController::class, 'history'])->name('admin.recognition.history');
Route::get('/admin/recognition/certificates', [RecognitionController::class, 'certificates'])->name('admin.recognition.certificates');
Route::get('/admin/recognition/analytics', [RecognitionController::class, 'analytics'])->name('admin.recognition.analytics');

Route::get('/admin/evaluation/driver-evaluation', [DriverEvaluationController::class, 'index'])->name('admin.evaluation.driver-evaluation');
Route::post('/admin/evaluation/driver-evaluation', [DriverEvaluationController::class, 'store'])->name('admin.evaluation.driver-evaluation.store');
Route::get('/admin/evaluation/driver-evaluation/{peerEvaluation}', [DriverEvaluationController::class, 'show'])->name('admin.evaluation.driver-evaluation.show');
Route::get('/admin/evaluation/driver-evaluation/{peerEvaluation}/edit', [DriverEvaluationController::class, 'edit'])->name('admin.evaluation.driver-evaluation.edit');
Route::put('/admin/evaluation/driver-evaluation/{peerEvaluation}', [DriverEvaluationController::class, 'update'])->name('admin.evaluation.driver-evaluation.update');
Route::delete('/admin/evaluation/driver-evaluation/{peerEvaluation}', [DriverEvaluationController::class, 'destroy'])->name('admin.evaluation.driver-evaluation.destroy');

Route::get('/admin/evaluation/review', [EvaluationReviewController::class, 'index'])->name('admin.evaluation.review');
Route::post('/admin/evaluation/review/{peerEvaluation}/approve', [EvaluationReviewController::class, 'approve'])->name('admin.evaluation.review.approve');
Route::post('/admin/evaluation/review/{peerEvaluation}/reject', [EvaluationReviewController::class, 'reject'])->name('admin.evaluation.review.reject');

Route::get('/admin/evaluation/feedback-summary', [FeedbackSummaryController::class, 'index'])->name('admin.evaluation.feedback-summary');
Route::get('/admin/evaluation/feedback-summary/{driverId}', [FeedbackSummaryController::class, 'show'])->name('admin.evaluation.feedback-summary.show');

Route::get('/admin/evaluation/reports', [EvaluationReportController::class, 'index'])->name('admin.evaluation.reports');
Route::post('/admin/evaluation/reports', [EvaluationReportController::class, 'store'])->name('admin.evaluation.reports.store');

Route::get('/admin/evaluation/analytics', [EvaluationAnalyticsController::class, 'index'])->name('admin.evaluation.analytics');

Route::get('/admin/evaluation/history', [EvaluationHistoryController::class, 'index'])->name('admin.evaluation.history');

Route::get('/admin/evaluation', function () {
    return redirect()->route('admin.evaluation.driver-evaluation');
})->name('admin.evaluation.index');

Route::get('/admin/reports/driver-reports', [ReportsController::class, 'driverReports'])->name('admin.reports.driver-reports');
Route::get('/admin/reports/evaluation-reports', [ReportsController::class, 'evaluationReports'])->name('admin.reports.evaluation-reports');
Route::get('/admin/reports/analytics-dashboard', [ReportsController::class, 'analyticsDashboard'])->name('admin.reports.analytics-dashboard');
Route::get('/admin/reports/data-visualization', [ReportsController::class, 'dataVisualization'])->name('admin.reports.data-visualization');
Route::get('/admin/reports/export-center', [ReportsController::class, 'exportCenter'])->name('admin.reports.export-center');
Route::get('/admin/reports/report-history', [ReportsController::class, 'reportHistory'])->name('admin.reports.report-history');
Route::post('/admin/reports', [ReportsController::class, 'store'])->name('admin.reports.store');
Route::post('/admin/reports/{report}/export', [ReportsController::class, 'export'])->name('admin.reports.export');

Route::get('/admin/reports', function () {
    return redirect()->route('admin.reports.driver-reports');
})->name('admin.reports.index');

Route::get('/admin/notifications/training', [TrainingNotificationsController::class, 'index'])->name('admin.notifications.training');
Route::post('/admin/notifications/training', [TrainingNotificationsController::class, 'store'])->name('admin.notifications.training.store');
Route::post('/admin/notifications/training/{notification}/read', [TrainingNotificationsController::class, 'markAsRead'])->name('admin.notifications.training.read');
Route::post('/admin/notifications/training/{notification}/archive', [TrainingNotificationsController::class, 'archive'])->name('admin.notifications.training.archive');
Route::delete('/admin/notifications/training/{notification}', [TrainingNotificationsController::class, 'destroy'])->name('admin.notifications.training.destroy');

Route::get('/admin/notifications/performance', [PerformanceNotificationsController::class, 'index'])->name('admin.notifications.performance');
Route::post('/admin/notifications/performance', [PerformanceNotificationsController::class, 'store'])->name('admin.notifications.performance.store');
Route::post('/admin/notifications/performance/{notification}/read', [PerformanceNotificationsController::class, 'markAsRead'])->name('admin.notifications.performance.read');
Route::post('/admin/notifications/performance/{notification}/archive', [PerformanceNotificationsController::class, 'archive'])->name('admin.notifications.performance.archive');

Route::get('/admin/notifications/announcements', [SystemAnnouncementsController::class, 'index'])->name('admin.notifications.announcements');
Route::post('/admin/notifications/announcements', [SystemAnnouncementsController::class, 'store'])->name('admin.notifications.announcements.store');
Route::post('/admin/notifications/announcements/{announcement}/publish', [SystemAnnouncementsController::class, 'publish'])->name('admin.notifications.announcements.publish');
Route::post('/admin/notifications/announcements/{announcement}/archive', [SystemAnnouncementsController::class, 'archive'])->name('admin.notifications.announcements.archive');
Route::delete('/admin/notifications/announcements/{announcement}', [SystemAnnouncementsController::class, 'destroy'])->name('admin.notifications.announcements.destroy');

Route::get('/admin/notifications/history', [NotificationHistoryController::class, 'index'])->name('admin.notifications.history');
Route::post('/admin/notifications/history/{id}/restore', [NotificationHistoryController::class, 'restore'])->name('admin.notifications.history.restore');
Route::delete('/admin/notifications/history/{id}', [NotificationHistoryController::class, 'destroy'])->name('admin.notifications.history.destroy');

Route::get('/admin/notifications/settings', [NotificationSettingsController::class, 'index'])->name('admin.notifications.settings');
Route::post('/admin/notifications/settings', [NotificationSettingsController::class, 'store'])->name('admin.notifications.settings.store');
Route::post('/admin/notifications/settings/reset', [NotificationSettingsController::class, 'reset'])->name('admin.notifications.settings.reset');
Route::post('/admin/notifications/settings/test', [NotificationSettingsController::class, 'test'])->name('admin.notifications.settings.test');

Route::get('/admin/notifications/logs', [NotificationLogsController::class, 'index'])->name('admin.notifications.logs');
Route::post('/admin/notifications/logs/{id}/retry', [NotificationLogsController::class, 'retry'])->name('admin.notifications.logs.retry');
Route::get('/admin/notifications/logs/export', [NotificationLogsController::class, 'export'])->name('admin.notifications.logs.export');

Route::get('/admin/notifications', function () {
    return redirect()->route('admin.notifications.training');
})->name('admin.notifications.index');

Route::get('/admin/users/accounts', [UserAccountsController::class, 'index'])->name('admin.users.accounts');
Route::post('/admin/users/accounts', [UserAccountsController::class, 'store'])->name('admin.users.accounts.store');
Route::get('/admin/users/accounts/{user}/edit', [UserAccountsController::class, 'edit'])->name('admin.users.accounts.edit');
Route::put('/admin/users/accounts/{user}', [UserAccountsController::class, 'update'])->name('admin.users.accounts.update');
Route::delete('/admin/users/accounts/{user}', [UserAccountsController::class, 'destroy'])->name('admin.users.accounts.destroy');
Route::post('/admin/users/accounts/{user}/activate', [UserAccountsController::class, 'activate'])->name('admin.users.activate');
Route::post('/admin/users/accounts/{user}/deactivate', [UserAccountsController::class, 'deactivate'])->name('admin.users.deactivate');
Route::post('/admin/users/accounts/{user}/lock', [UserAccountsController::class, 'lock'])->name('admin.users.lock');
Route::post('/admin/users/accounts/{user}/unlock', [UserAccountsController::class, 'unlock'])->name('admin.users.unlock');
Route::post('/admin/users/accounts/{user}/reset-password', [UserAccountsController::class, 'resetPassword'])->name('admin.users.reset-password');

Route::get('/admin/users/roles', [UserRolesPermissionsController::class, 'index'])->name('admin.users.roles');
Route::post('/admin/users/roles', [UserRolesPermissionsController::class, 'store'])->name('admin.users.roles.store');
Route::put('/admin/users/roles/{role}', [UserRolesPermissionsController::class, 'update'])->name('admin.users.roles.update');
Route::delete('/admin/users/roles/{role}', [UserRolesPermissionsController::class, 'destroy'])->name('admin.users.roles.destroy');

Route::get('/admin/users/account-management', [AccountManagementController::class, 'index'])->name('admin.users.account-management');

Route::get('/admin/users/login-logs', [LoginActivityLogsController::class, 'index'])->name('admin.users.login-logs');

Route::get('/admin/users/security', [SecurityMonitoringController::class, 'index'])->name('admin.users.security');

Route::get('/admin/users/audit-logs', [AuditLogsController::class, 'index'])->name('admin.users.audit-logs');

Route::get('/admin/users', function () {
    return redirect()->route('admin.users.accounts');
})->name('admin.users.index');

Route::middleware('web')->prefix('admin/settings')->name('admin.settings.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.settings.agency.index');
    })->name('index');

    Route::get('/agency', [App\Http\Controllers\Settings\AgencySettingsController::class, 'index'])->name('agency.index');
    Route::put('/agency', [App\Http\Controllers\Settings\AgencySettingsController::class, 'update'])->name('agency.update');

    Route::get('/preferences', [App\Http\Controllers\Settings\SystemPreferencesController::class, 'index'])->name('preferences.index');
    Route::put('/preferences', [App\Http\Controllers\Settings\SystemPreferencesController::class, 'update'])->name('preferences.update');
    Route::post('/preferences/restore', [App\Http\Controllers\Settings\SystemPreferencesController::class, 'restoreDefaults'])->name('preferences.restore');

    Route::get('/security', [App\Http\Controllers\Settings\SecuritySettingsController::class, 'index'])->name('security.index');
    Route::put('/security', [App\Http\Controllers\Settings\SecuritySettingsController::class, 'update'])->name('security.update');
    Route::post('/security/change-password', [App\Http\Controllers\Settings\SecuritySettingsController::class, 'changePassword'])->name('security.password');
    Route::post('/security/force-logout', [App\Http\Controllers\Settings\SecuritySettingsController::class, 'forceLogoutAll'])->name('security.force-logout');

    Route::get('/appearance', [App\Http\Controllers\Settings\AppearanceLocalizationController::class, 'index'])->name('appearance.index');
    Route::put('/appearance', [App\Http\Controllers\Settings\AppearanceLocalizationController::class, 'update'])->name('appearance.update');
    Route::post('/appearance/restore', [App\Http\Controllers\Settings\AppearanceLocalizationController::class, 'restoreDefaults'])->name('appearance.restore');

    Route::get('/backup', [App\Http\Controllers\Settings\BackupRecoveryController::class, 'index'])->name('backup.index');
    Route::post('/backup', [App\Http\Controllers\Settings\BackupRecoveryController::class, 'store'])->name('backup.store');
    Route::get('/backup/{backup}/download', [App\Http\Controllers\Settings\BackupRecoveryController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{backup}', [App\Http\Controllers\Settings\BackupRecoveryController::class, 'destroy'])->name('backup.destroy');

    Route::get('/logs', [App\Http\Controllers\Settings\SystemLogsController::class, 'index'])->name('logs.index');
});

// Logout Route — implement when auth is set up
Route::post('/logout', function () {
    // TODO: Auth::logout(); session()->invalidate(); session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// API Routes
Route::prefix('api/training')->name('api.training.')->group(function () {
    Route::get('/', [TrainingApiController::class, 'index'])->name('index');
    Route::get('/dashboard', [TrainingApiController::class, 'dashboard'])->name('dashboard');
    Route::post('/', [TrainingApiController::class, 'store'])->name('store');
    Route::get('/{training}', [TrainingApiController::class, 'show'])->name('show');
    Route::put('/{training}', [TrainingApiController::class, 'update'])->name('update');
    Route::delete('/{training}', [TrainingApiController::class, 'destroy'])->name('destroy');
});
