<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrainingApiController;

Route::prefix('training')->name('api.training.')->group(function () {
    Route::get('/', [TrainingApiController::class, 'index'])->name('index');
    Route::get('/dashboard', [TrainingApiController::class, 'dashboard'])->name('dashboard');
    Route::post('/', [TrainingApiController::class, 'store'])->name('store');
    Route::get('/{training}', [TrainingApiController::class, 'show'])->name('show');
    Route::put('/{training}', [TrainingApiController::class, 'update'])->name('update');
    Route::delete('/{training}', [TrainingApiController::class, 'destroy'])->name('destroy');
});
