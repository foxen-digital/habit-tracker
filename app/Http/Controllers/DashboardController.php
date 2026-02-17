<?php

namespace App\Http\Controllers;

use App\Models\DailyGoal;
use App\Models\MoodEntry;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\WeightEntry;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();

        return view('dashboard', [
            // Stats
            'weightProgress' => WeightEntry::getGoalProgress(),
            'walkStats' => WalkEntry::getWeeklyStats(),
            'waterToday' => WaterEntry::getTodayIntake(),
            'moodTrend' => MoodEntry::getWeeklyMoodTrend(),

            // Recent entries for lists
            'recentWeights' => WeightEntry::orderBy('date', 'desc')->take(7)->get(),
            'recentWalks' => WalkEntry::orderBy('date', 'desc')->take(7)->get(),

            // Chart data (contiguous days)
            'weightChart' => WeightEntry::getChartData(14),
            'walkChart' => WalkEntry::getChartData(7),
            'waterChart' => WaterEntry::getChartData(7),
            'moodChart' => MoodEntry::getChartData(7),

            // Daily goals
            'dailyGoals' => DailyGoal::getActiveGoals(),
            'dailyGoalStats' => DailyGoal::getStatsForDate($today),
            'weeklyGoalStats' => DailyGoal::getWeeklyStats(),
        ]);
    }
}
