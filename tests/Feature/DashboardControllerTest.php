<?php

use App\Models\DailyGoal;
use App\Models\MoodEntry;
use App\Models\User;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\WeightEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $this->actingAs($this->user);
});

describe('DashboardController', function () {
    it('returns the dashboard view', function () {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewIs('dashboard');
    });

    it('passes weight progress to view', function () {
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 100, 'date' => '2026-01-01']);
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 95, 'date' => '2026-02-17']);

        $response = $this->get('/');

        $response->assertViewHas('weightProgress', function ($progress) {
            return $progress['lost'] === 5.0;
        });
    });

    it('passes walk stats to view', function () {
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 2, 'steps' => 4000, 'date' => '2026-02-17']);

        $response = $this->get('/');

        $response->assertViewHas('walkStats', function ($stats) {
            return $stats['total_miles'] === 2.0;
        });
    });

    it('passes water intake to view', function () {
        WaterEntry::create(['user_id' => $this->user->id, 'glasses' => 6, 'date' => '2026-02-17']);

        $response = $this->get('/');

        $response->assertViewHas('waterToday', 6);
    });

    it('passes mood trend to view', function () {
        MoodEntry::create([
            'user_id' => $this->user->id,
            'mood' => 'good',
            'energy_level' => 7,
            'date' => '2026-02-17',
        ]);

        $response = $this->get('/');

        $response->assertViewHas('moodTrend');
    });

    it('passes recent weights to view', function () {
        // Use unique dates to avoid constraint violations
        for ($i = 0; $i < 10; $i++) {
            WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 80 + $i, 'date' => '2026-02-'.sprintf('%02d', $i + 1)]);
        }

        $response = $this->get('/');

        $response->assertViewHas('recentWeights', function ($weights) {
            return $weights->count() === 7;
        });
    });

    it('passes recent walks to view', function () {
        // Use unique dates to avoid constraint violations
        for ($i = 0; $i < 10; $i++) {
            WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 1 + $i, 'steps' => 1000, 'date' => '2026-02-'.sprintf('%02d', $i + 1)]);
        }

        $response = $this->get('/');

        $response->assertViewHas('recentWalks', function ($walks) {
            return $walks->count() === 7;
        });
    });

    it('passes chart data to view', function () {
        $response = $this->get('/');

        $response->assertViewHas('weightChart')
            ->assertViewHas('walkChart')
            ->assertViewHas('waterChart')
            ->assertViewHas('moodChart');
    });

    it('passes daily goals to view', function () {
        DailyGoal::factory()->count(3)->create(['user_id' => $this->user->id, 'is_active' => true]);
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'is_active' => false]);

        $response = $this->get('/');

        $response->assertViewHas('dailyGoals', function ($goals) {
            return $goals->count() === 3;
        });
    });

    it('passes daily goal stats to view', function () {
        DailyGoal::factory()->count(3)->create(['user_id' => $this->user->id, 'is_active' => true]);

        $response = $this->get('/');

        $response->assertViewHas('dailyGoalStats')
            ->assertViewHas('weeklyGoalStats');
    });

    it('handles empty database gracefully', function () {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertViewHas('weightProgress')
            ->assertViewHas('walkStats')
            ->assertViewHas('waterToday', 0);
    });
});
