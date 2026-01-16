<?php

use Illuminate\Support\Facades\Route;
use PhpuanJck\Http\Controllers\DashboardController;

Route::prefix('phpuan-jck')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('phpuan-jck.dashboard');
    Route::get('/traces', [DashboardController::class, 'traces'])->name('phpuan-jck.traces');
    Route::get('/detail/{id}', [DashboardController::class, 'show'])->name('phpuan-jck.detail');
    Route::get('/problems', [DashboardController::class, 'problems'])->name('phpuan-jck.problems');
    Route::get('/call-path', [DashboardController::class, 'callPath'])->name('phpuan-jck.call-path');
    Route::post('/clear-cache', [DashboardController::class, 'clearCache'])->name('phpuan-jck.clear-cache');
    Route::get('/telescope', [DashboardController::class, 'telescope'])->name('phpuan-jck.telescope');
});
