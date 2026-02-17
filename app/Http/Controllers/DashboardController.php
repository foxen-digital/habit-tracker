<?php

namespace App\Http\Controllers;

use App\Models\WeightEntry;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\MoodEntry;
use App\Models\DailyGoal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();

        return view('dashboard', [
            'weightProgress' => WeightEntry::getGoalProgress(),
            'walkStats' => WalkEntry::getWeeklyStats(),
            'waterToday' => WaterEntry::getTodayIntake(),
            'moodTrend' => MoodEntry::getWeeklyMoodTrend(),
            'recentWeights' => WeightEntry::orderBy('date', 'desc')->take(7)->get(),
            'recentWalks' => WalkEntry::orderBy('date', 'desc')->take(7)->get(),
            'dailyGoals' => DailyGoal::getActiveGoals(),
            'dailyGoalStats' => DailyGoal::getStatsForDate($today),
            'weeklyGoalStats' => DailyGoal::getWeeklyStats(),
        ]);
    }
}
