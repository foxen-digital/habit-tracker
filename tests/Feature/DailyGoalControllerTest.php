<?php

use App\Models\DailyGoal;
use App\Models\DailyGoalCompletion;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
});

describe('DailyGoalController::store', function () {
    it('creates a goal and redirects', function () {
        $response = $this->post('/goals', [
            'name' => 'Hit Calorie Target',
            'emoji' => '🎯',
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Goal created!');

        expect(DailyGoal::count())->toBe(1)
            ->and(DailyGoal::first()->name)->toBe('Hit Calorie Target')
            ->and(DailyGoal::first()->emoji)->toBe('🎯')
            ->and(DailyGoal::first()->is_active)->toBeTrue();
    });

    it('sets default emoji if not provided', function () {
        $this->post('/goals', ['name' => 'Test Goal'])
            ->assertSessionHasNoErrors();

        expect(DailyGoal::first()->emoji)->toBe('✅');
    });

    it('sets sort order for new goals', function () {
        $this->post('/goals', ['name' => 'First Goal']);
        $this->post('/goals', ['name' => 'Second Goal']);

        $goals = DailyGoal::orderBy('id')->get();
        expect($goals->first()->sort_order)->toBe(1)
            ->and($goals->last()->sort_order)->toBe(2);
    });

    it('validates name is required', function () {
        $this->post('/goals', ['emoji' => '🎯'])
            ->assertSessionHasErrors(['name']);
    });

    it('validates name max length', function () {
        $this->post('/goals', ['name' => str_repeat('a', 101)])
            ->assertSessionHasErrors(['name']);
    });
});

describe('DailyGoalController::toggleCompletion', function () {
    it('creates a completion record', function () {
        $goal = DailyGoal::factory()->create();

        $response = $this->post("/goals/{$goal->id}/toggle", [
            'date' => '2026-02-17',
            'completed' => true,
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Goal marked as complete!');

        expect(DailyGoalCompletion::count())->toBe(1)
            ->and(DailyGoalCompletion::first()->completed)->toBeTrue();
    });

    it('validates date is required', function () {
        $goal = DailyGoal::factory()->create();

        $this->post("/goals/{$goal->id}/toggle", ['completed' => true])
            ->assertSessionHasErrors(['date']);
    });

    it('validates completed is boolean', function () {
        $goal = DailyGoal::factory()->create();

        $this->post("/goals/{$goal->id}/toggle", [
            'date' => '2026-02-17',
            'completed' => 'yes',
        ])->assertSessionHasErrors(['completed']);
    });
});

describe('DailyGoalController::update', function () {
    it('updates goal name', function () {
        $goal = DailyGoal::factory()->create(['name' => 'Old Name']);

        $this->patch("/goals/{$goal->id}", ['name' => 'New Name'])
            ->assertSessionHas('success', 'Goal updated!');

        expect($goal->fresh()->name)->toBe('New Name');
    });

    it('can deactivate a goal', function () {
        $goal = DailyGoal::factory()->create(['is_active' => true]);

        $this->patch("/goals/{$goal->id}", ['is_active' => false])
            ->assertSessionHasNoErrors();

        expect($goal->fresh()->is_active)->toBeFalse();
    });

    it('can update emoji', function () {
        $goal = DailyGoal::factory()->create(['emoji' => '✅']);

        $this->patch("/goals/{$goal->id}", ['emoji' => '🎯'])
            ->assertSessionHasNoErrors();

        expect($goal->fresh()->emoji)->toBe('🎯');
    });
});

describe('DailyGoalController::destroy', function () {
    it('deletes a goal', function () {
        $goal = DailyGoal::factory()->create();

        $response = $this->delete("/goals/{$goal->id}");

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Goal deleted!');

        expect(DailyGoal::find($goal->id))->toBeNull();
    });
});
