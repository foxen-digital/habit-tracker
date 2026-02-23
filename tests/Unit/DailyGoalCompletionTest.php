<?php

use App\Models\DailyGoal;
use App\Models\DailyGoalCompletion;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create();
});

describe('DailyGoalCompletion model', function () {
    it('can create a completion', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);
        $completion = DailyGoalCompletion::factory()->create([
            'daily_goal_id' => $goal->id,
            'date' => '2026-02-17',
            'completed' => true,
        ]);

        expect($completion->daily_goal_id)->toBe($goal->id)
            ->and($completion->completed)->toBeTrue();
    });

    it('casts date to carbon instance', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);
        $completion = DailyGoalCompletion::factory()->create(['daily_goal_id' => $goal->id]);

        expect($completion->date)->toBeInstanceOf(Carbon::class);
    });

    it('casts completed to boolean', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);
        $completion = DailyGoalCompletion::create([
            'user_id' => $this->user->id,
            'daily_goal_id' => $goal->id,
            'date' => '2026-02-17',
            'completed' => 1,
        ]);

        expect($completion->completed)->toBeTrue();
    });

    it('belongs to a goal', function () {
        $goal = DailyGoal::factory()->create(['user_id' => $this->user->id]);
        $completion = DailyGoalCompletion::factory()->create(['daily_goal_id' => $goal->id]);

        expect($completion->goal->id)->toBe($goal->id);
    });
});
