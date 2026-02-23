<?php

namespace App\Http\Controllers;

use App\Models\DailyGoal;
use App\Models\GlucoseEntry;
use App\Models\MoodEntry;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\WeightEntry;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = request()->user();
        $today = Carbon::today();

        return view('dashboard', [
            // User info
            'user' => $user,
            'settings' => $user->getSettings(),

            // Stats
            'weightProgress' => WeightEntry::getGoalProgress($user),
            'walkStats' => WalkEntry::getWeeklyStats($user),
            'waterToday' => WaterEntry::getTodayIntake($user),
            'moodTrend' => MoodEntry::getWeeklyMoodTrend($user),
            'glucoseStats' => GlucoseEntry::getWeeklyStats($user),

            // Recent entries for lists
            'recentWeights' => WeightEntry::forUser($user)->orderBy('date', 'desc')->take(7)->get(),
            'recentWalks' => WalkEntry::forUser($user)->orderBy('date', 'desc')->take(7)->get(),
            'recentGlucose' => GlucoseEntry::forUser($user)->orderBy('measured_at', 'desc')->take(7)->get(),

            // Chart data (contiguous days)
            'weightChart' => WeightEntry::getChartData($user, 14),
            'walkChart' => WalkEntry::getChartData($user, 7),
            'waterChart' => WaterEntry::getChartData($user, 7),
            'moodChart' => MoodEntry::getChartData($user, 7),
            'glucoseChart' => GlucoseEntry::getChartData($user, 7),

            // Daily goals
            'dailyGoals' => DailyGoal::getActiveGoals($user),
            'dailyGoalStats' => DailyGoal::getStatsForDate($user, $today),
            'weeklyGoalStats' => DailyGoal::getWeeklyStats($user),
        ]);
    }
}
