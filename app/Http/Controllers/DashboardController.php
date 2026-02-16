<?php

namespace App\Http\Controllers;

use App\Models\WeightEntry;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\MoodEntry;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'weightProgress' => WeightEntry::getGoalProgress(),
            'walkStats' => WalkEntry::getWeeklyStats(),
            'waterToday' => WaterEntry::getTodayIntake(),
            'moodTrend' => MoodEntry::getWeeklyMoodTrend(),
            'recentWeights' => WeightEntry::orderBy('date', 'desc')->take(7)->get(),
            'recentWalks' => WalkEntry::orderBy('date', 'desc')->take(7)->get(),
        ]);
    }
}
