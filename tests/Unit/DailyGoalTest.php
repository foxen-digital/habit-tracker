<?php

use App\Models\DailyGoal;
use App\Models\DailyGoalCompletion;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create();
});

describe('DailyGoal model', function () {
    it('can create a daily goal', function () {
        $goal = DailyGoal::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Hit Calorie Target',
            'emoji' => '🎯',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        expect($goal->name)->toBe('Hit Calorie Target')
            ->and($goal->emoji)->toBe('🎯')
            ->and($goal->is_active)->toBeTrue()
            ->and($goal->sort_order)->toBe(1);
    });

    it('casts is_active to boolean', function () {
        $goal = DailyGoal::create([
            'user_id' => $this->user->id,
            'name' => 'Test Goal',
            'is_active' => 1,
        ]);

        expect($goal->is_active)->toBeTrue();
    });

    it('has many completions', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);
        $completion = DailyGoalCompletion::factory()->create(['daily_goal_id' => $goal->id]);

        expect($goal->completions)->toHaveCount(1)
            ->and($goal->completions->first()->id)->toBe($completion->id);
    });
});

describe('DailyGoal::getCompletionForDate', function () {
    it('returns null when no completion exists', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);

        $completion = $goal->getCompletionForDate(Carbon::today());

        expect($completion)->toBeNull();
    });
});

describe('DailyGoal::isCompletedForDate', function () {
    it('returns false when no completion exists', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);

        expect($goal->isCompletedForDate(Carbon::today()))->toBeFalse();
    });
});

describe('DailyGoal::getActiveGoals', function () {
    it('returns only active goals for user', function () {
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'name' => 'Active Goal', 'is_active' => true]);
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'name' => 'Inactive Goal', 'is_active' => false]);

        $goals = DailyGoal::getActiveGoals($this->user);

        expect($goals)->toHaveCount(1)
            ->and($goals->first()->name)->toBe('Active Goal');
    });

    it('orders by sort_order then name', function () {
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'name' => 'Zebra', 'sort_order' => 1]);
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'name' => 'Apple', 'sort_order' => 2]);
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'name' => 'Banana', 'sort_order' => 1]);

        $goals = DailyGoal::getActiveGoals($this->user);

        expect($goals->get(0)->name)->toBe('Banana') // sort_order 1, alphabetically first
            ->and($goals->get(1)->name)->toBe('Zebra') // sort_order 1, alphabetically second
            ->and($goals->get(2)->name)->toBe('Apple'); // sort_order 2
    });
});

describe('DailyGoal::getStatsForDate', function () {
    it('returns zero stats when no goals exist', function () {
        $stats = DailyGoal::getStatsForDate($this->user, Carbon::today());

        expect($stats['total'])->toBe(0)
            ->and($stats['completed'])->toBe(0)
            ->and($stats['percent'])->toBe(0);
    });
});

describe('DailyGoal::getWeeklyStats', function () {
    it('returns stats for 7 days', function () {
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'is_active' => true]);

        $stats = DailyGoal::getWeeklyStats($this->user);

        expect($stats)->toHaveCount(7);
    });

    it('includes correct day labels', function () {
        DailyGoal::factory()->create(['user_id' => $this->user->id, 'is_active' => true]);

        $stats = DailyGoal::getWeeklyStats($this->user);

        $days = collect($stats)->pluck('date')->toArray();
        expect($days)->toContain('Mon')
            ->and($days)->toContain('Tue')
            ->and($days)->toContain('Sun');
    });
});
