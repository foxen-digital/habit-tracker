<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\DailyGoalController;

Route::get('/', DashboardController::class);

// Entry routes
Route::post('/weight', [EntryController::class, 'storeWeight'])->name('weight.store');
Route::post('/walk', [EntryController::class, 'storeWalk'])->name('walk.store');
Route::post('/water', [EntryController::class, 'storeWater'])->name('water.store');
Route::post('/mood', [EntryController::class, 'storeMood'])->name('mood.store');

// Daily Goal routes
Route::post('/goals', [DailyGoalController::class, 'store'])->name('goals.store');
Route::post('/goals/{goal}/toggle', [DailyGoalController::class, 'toggleCompletion'])->name('goals.toggle');
Route::delete('/goals/{goal}', [DailyGoalController::class, 'destroy'])->name('goals.destroy');
Route::patch('/goals/{goal}', [DailyGoalController::class, 'update'])->name('goals.update');
