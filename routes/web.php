<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntryController;

Route::get('/', DashboardController::class);

// Entry routes
Route::post('/weight', [EntryController::class, 'storeWeight'])->name('weight.store');
Route::post('/walk', [EntryController::class, 'storeWalk'])->name('walk.store');
Route::post('/water', [EntryController::class, 'storeWater'])->name('water.store');
Route::post('/mood', [EntryController::class, 'storeMood'])->name('mood.store');
