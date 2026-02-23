<?php

use App\Models\User;
use App\Models\WeightEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create();
});

describe('WeightEntry model', function () {
    it('can create a weight entry', function () {
        $entry = WeightEntry::factory()->create([
            'user_id' => $this->user->id,
            'weight_kg' => 85.5,
            'date' => '2026-02-17',
        ]);

        expect((float) $entry->weight_kg)->toEqual(85.5)
            ->and($entry->date->format('Y-m-d'))->toBe('2026-02-17');
    });

    it('casts weight to decimal with 2 places', function () {
        $entry = WeightEntry::create([
            'user_id' => $this->user->id,
            'weight_kg' => 85.123456,
            'date' => '2026-02-17',
        ]);

        expect((float) $entry->weight_kg)->toEqual(85.12);
    });

    it('casts date to carbon instance', function () {
        $entry = WeightEntry::factory()->create(['user_id' => $this->user->id]);

        expect($entry->date)->toBeInstanceOf(Carbon::class);
    });
});

describe('WeightEntry::getGoalProgress', function () {
    it('returns null values when no entries exist', function () {
        $progress = WeightEntry::getGoalProgress($this->user);

        expect($progress['current'])->toBeNull()
            ->and($progress['start'])->toBeNull()
            ->and($progress['lost'])->toBe(0)
            ->and((float) $progress['goal'])->toBe(25.0);
    });

    it('calculates progress correctly with entries', function () {
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 100, 'date' => '2026-01-01']);
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 95, 'date' => '2026-02-17']);

        $progress = WeightEntry::getGoalProgress($this->user);

        expect((float) $progress['current'])->toEqual(95.0)
            ->and((float) $progress['start'])->toEqual(100.0)
            ->and($progress['lost'])->toBe(5.0)
            ->and((float) $progress['goal'])->toBe(25.0)
            ->and($progress['progress_percent'])->toBe(20.0);
    });

    it('does not show negative weight loss', function () {
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 90, 'date' => '2026-01-01']);
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 95, 'date' => '2026-02-17']);

        $progress = WeightEntry::getGoalProgress($this->user);

        expect($progress['lost'])->toBe(0)
            ->and((float) $progress['progress_percent'])->toBe(0.0);
    });

    it('caps progress at 100 percent', function () {
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 100, 'date' => '2026-01-01']);
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 70, 'date' => '2026-02-17']);

        $progress = WeightEntry::getGoalProgress($this->user);

        expect($progress['progress_percent'])->toBe(100);
    });
});

describe('WeightEntry::getChartData', function () {
    it('returns correct number of days', function () {
        $chart = WeightEntry::getChartData($this->user, 7);

        expect($chart['labels'])->toHaveCount(7)
            ->and($chart['data'])->toHaveCount(7);
    });

    it('fills gaps with last known weight', function () {
        WeightEntry::create(['user_id' => $this->user->id, 'weight_kg' => 85, 'date' => '2026-02-15']);

        $chart = WeightEntry::getChartData($this->user, 3);

        // Index 0 = today (Feb 17), 1 = yesterday (Feb 16), 2 = Feb 15
        expect((float) $chart['data'][2])->toEqual(85.0); // Feb 15
        expect((float) $chart['data'][1])->toEqual(85.0); // Feb 16 (carried)
        expect((float) $chart['data'][0])->toEqual(85.0); // Feb 17 (carried)
    });

    it('returns null for days with no prior data', function () {
        $chart = WeightEntry::getChartData($this->user, 3);

        // No entries exist, so all should be null
        expect($chart['data'])->each->toBeNull();
    });
});
