<?php

use App\Http\Controllers\DailyGoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Fortify handles authentication routes (login, register, password reset, etc.)
| automatically. All application routes below require authentication and
| email verification.
|
*/

// All application routes require authentication + email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/', DashboardController::class)->name('dashboard');

    // Entry routes
    Route::post('/weight', [EntryController::class, 'storeWeight'])->name('weight.store');
    Route::post('/walk', [EntryController::class, 'storeWalk'])->name('walk.store');
    Route::post('/water', [EntryController::class, 'storeWater'])->name('water.store');
    Route::post('/mood', [EntryController::class, 'storeMood'])->name('mood.store');
    Route::post('/glucose', [EntryController::class, 'storeGlucose'])->name('glucose.store');

    // Daily Goal routes
    Route::post('/goals', [DailyGoalController::class, 'store'])->name('goals.store');
    Route::post('/goals/{goal}/toggle', [DailyGoalController::class, 'toggleCompletion'])->name('goals.toggle');
    Route::delete('/goals/{goal}', [DailyGoalController::class, 'destroy'])->name('goals.destroy');
    Route::patch('/goals/{goal}', [DailyGoalController::class, 'update'])->name('goals.update');

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
