<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\DriverApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\TrainingApiController;
use App\Http\Controllers\Api\PerformanceApiController;
use App\Http\Controllers\Api\CompetencyApiController;
use App\Http\Controllers\Api\LearningApiController;
use App\Http\Controllers\Api\PeerEvaluationApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\SettingApiController;

// ─────────────────────────────────────────────────────────────
// Public Auth API
// ─────────────────────────────────────────────────────────────
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::get('/user', [ApiAuthController::class, 'user'])->name('user');
    Route::post('/refresh', [ApiAuthController::class, 'refresh'])->name('refresh');
});

// ─────────────────────────────────────────────────────────────
// Admin-only REST API
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin/api')->name('api.admin.')->group(function () {
    Route::prefix('drivers')->name('drivers.')->group(function () {
        Route::get('/', [DriverApiController::class, 'index'])->name('index');
        Route::post('/', [DriverApiController::class, 'store'])->name('store');
        Route::get('/{driver}', [DriverApiController::class, 'show'])->name('show');
        Route::put('/{driver}', [DriverApiController::class, 'update'])->name('update');
        Route::patch('/{driver}/status', [DriverApiController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{driver}', [DriverApiController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [DriverApiController::class, 'export'])->name('export');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserApiController::class, 'index'])->name('index');
        Route::post('/', [UserApiController::class, 'store'])->name('store');
        Route::get('/{user}', [UserApiController::class, 'show'])->name('show');
        Route::put('/{user}', [UserApiController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserApiController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/activate', [UserApiController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [UserApiController::class, 'deactivate'])->name('deactivate');
    });

    Route::prefix('training')->name('training.')->group(function () {
        Route::get('/', [TrainingApiController::class, 'index'])->name('index');
        Route::get('/dashboard', [TrainingApiController::class, 'dashboard'])->name('dashboard');
        Route::post('/', [TrainingApiController::class, 'store'])->name('store');
        Route::get('/{training}', [TrainingApiController::class, 'show'])->name('show');
        Route::put('/{training}', [TrainingApiController::class, 'update'])->name('update');
        Route::delete('/{training}', [TrainingApiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('performance')->name('performance.')->group(function () {
        Route::get('/', [PerformanceApiController::class, 'index'])->name('index');
        Route::get('/{performance}', [PerformanceApiController::class, 'show'])->name('show');
        Route::post('/', [PerformanceApiController::class, 'store'])->name('store');
        Route::put('/{performance}', [PerformanceApiController::class, 'update'])->name('update');
        Route::delete('/{performance}', [PerformanceApiController::class, 'destroy'])->name('destroy');
        Route::get('/stats', [PerformanceApiController::class, 'stats'])->name('stats');
    });

    Route::prefix('competency')->name('competency.')->group(function () {
        Route::get('/', [CompetencyApiController::class, 'index'])->name('index');
        Route::get('/{competency}', [CompetencyApiController::class, 'show'])->name('show');
        Route::post('/', [CompetencyApiController::class, 'store'])->name('store');
        Route::put('/{competency}', [CompetencyApiController::class, 'update'])->name('update');
        Route::delete('/{competency}', [CompetencyApiController::class, 'destroy'])->name('destroy');
        Route::get('/assessments', [CompetencyApiController::class, 'assessments'])->name('assessments');
    });

    Route::prefix('learning')->name('learning.')->group(function () {
        Route::get('/modules', [LearningApiController::class, 'modules'])->name('modules');
        Route::get('/modules/{module}', [LearningApiController::class, 'showModule'])->name('modules.show');
        Route::post('/modules', [LearningApiController::class, 'storeModule'])->name('modules.store');
        Route::put('/modules/{module}', [LearningApiController::class, 'updateModule'])->name('modules.update');
        Route::delete('/modules/{module}', [LearningApiController::class, 'destroyModule'])->name('modules.destroy');
        Route::get('/assessments', [LearningApiController::class, 'assessments'])->name('assessments');
        Route::get('/certificates', [LearningApiController::class, 'certificates'])->name('certificates');
    });

    Route::prefix('peer-evaluation')->name('peer-evaluation.')->group(function () {
        Route::get('/', [PeerEvaluationApiController::class, 'index'])->name('index');
        Route::get('/{evaluation}', [PeerEvaluationApiController::class, 'show'])->name('show');
        Route::post('/', [PeerEvaluationApiController::class, 'store'])->name('store');
        Route::put('/{evaluation}', [PeerEvaluationApiController::class, 'update'])->name('update');
        Route::delete('/{evaluation}', [PeerEvaluationApiController::class, 'destroy'])->name('destroy');
        Route::post('/{evaluation}/approve', [PeerEvaluationApiController::class, 'approve'])->name('approve');
        Route::post('/{evaluation}/reject', [PeerEvaluationApiController::class, 'reject'])->name('reject');
        Route::get('/summary/{driverId}', [PeerEvaluationApiController::class, 'summary'])->name('summary');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationApiController::class, 'index'])->name('index');
        Route::post('/', [NotificationApiController::class, 'store'])->name('store');
        Route::get('/{notification}', [NotificationApiController::class, 'show'])->name('show');
        Route::post('/{notification}/read', [NotificationApiController::class, 'markAsRead'])->name('read');
        Route::post('/{notification}/archive', [NotificationApiController::class, 'archive'])->name('archive');
        Route::delete('/{notification}', [NotificationApiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportApiController::class, 'index'])->name('index');
        Route::post('/', [ReportApiController::class, 'store'])->name('store');
        Route::get('/{report}', [ReportApiController::class, 'show'])->name('show');
        Route::post('/{report}/export', [ReportApiController::class, 'export'])->name('export');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/agency', [SettingApiController::class, 'agency'])->name('agency');
        Route::put('/agency', [SettingApiController::class, 'updateAgency'])->name('agency.update');
        Route::get('/preferences', [SettingApiController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [SettingApiController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/security', [SettingApiController::class, 'security'])->name('security');
        Route::put('/security', [SettingApiController::class, 'updateSecurity'])->name('security.update');
    });
});

// ─────────────────────────────────────────────────────────────
// Driver-only REST API
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'driver'])->prefix('driver/api')->name('api.driver.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Api\DriverApiController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Api\DriverApiController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Api\DriverApiController::class, 'updateProfile'])->name('profile.update');

    Route::get('/performance', [PerformanceApiController::class, 'myPerformance'])->name('performance');
    Route::get('/performance/{performance}', [PerformanceApiController::class, 'show'])->name('performance.show');

    Route::get('/competency', [CompetencyApiController::class, 'myCompetency'])->name('competency');
    Route::get('/competency/{competency}', [CompetencyApiController::class, 'show'])->name('competency.show');

    Route::get('/learning/modules', [LearningApiController::class, 'modules'])->name('learning.modules');
    Route::get('/learning/modules/{module}', [LearningApiController::class, 'showModule'])->name('learning.modules.show');
    Route::get('/learning/certificates', [LearningApiController::class, 'certificates'])->name('learning.certificates');

    Route::get('/training', [TrainingApiController::class, 'myTraining'])->name('training');
    Route::get('/training/{training}', [TrainingApiController::class, 'show'])->name('training.show');

    Route::get('/evaluations', [PeerEvaluationApiController::class, 'myEvaluations'])->name('evaluations');
    Route::get('/evaluations/{evaluation}', [PeerEvaluationApiController::class, 'show'])->name('evaluations.show');
    Route::post('/evaluations', [PeerEvaluationApiController::class, 'store'])->name('evaluations.store');

    Route::get('/notifications', [NotificationApiController::class, 'myNotifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/reports', [ReportApiController::class, 'myReports'])->name('reports');
});

// ─────────────────────────────────────────────────────────────
// Shared authenticated REST API (no role restriction)
// ─────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('drivers')->name('api.drivers.')->group(function () {
        Route::get('/', [DriverApiController::class, 'index'])->name('index');
        Route::post('/', [DriverApiController::class, 'store'])->name('store');
        Route::get('/{driver}', [DriverApiController::class, 'show'])->name('show');
        Route::put('/{driver}', [DriverApiController::class, 'update'])->name('update');
        Route::patch('/{driver}/status', [DriverApiController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{driver}', [DriverApiController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [DriverApiController::class, 'export'])->name('export');
    });

    Route::prefix('users')->name('api.users.')->group(function () {
        Route::get('/', [UserApiController::class, 'index'])->name('index');
        Route::post('/', [UserApiController::class, 'store'])->name('store');
        Route::get('/{user}', [UserApiController::class, 'show'])->name('show');
        Route::put('/{user}', [UserApiController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserApiController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/activate', [UserApiController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [UserApiController::class, 'deactivate'])->name('deactivate');
    });

    Route::prefix('reports')->name('api.reports.')->group(function () {
        Route::get('/', [ReportApiController::class, 'index'])->name('index');
        Route::post('/', [ReportApiController::class, 'store'])->name('store');
        Route::get('/{report}', [ReportApiController::class, 'show'])->name('show');
        Route::post('/{report}/export', [ReportApiController::class, 'export'])->name('export');
    });

    Route::prefix('settings')->name('api.settings.')->group(function () {
        Route::get('/agency', [SettingApiController::class, 'agency'])->name('agency');
        Route::put('/agency', [SettingApiController::class, 'updateAgency'])->name('agency.update');
        Route::get('/preferences', [SettingApiController::class, 'preferences'])->name('preferences');
        Route::put('/preferences', [SettingApiController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/security', [SettingApiController::class, 'security'])->name('security');
        Route::put('/security', [SettingApiController::class, 'updateSecurity'])->name('security.update');
    });
});
