<?php

use App\Models\User;
use App\Models\WaterEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create();
});

describe('WaterEntry model', function () {
    it('can create a water entry', function () {
        $entry = WaterEntry::factory()->create([
            'user_id' => $this->user->id,
            'glasses' => 6,
            'date' => '2026-02-17',
        ]);

        expect($entry->glasses)->toBe(6)
            ->and($entry->date->format('Y-m-d'))->toBe('2026-02-17');
    });

    it('casts date to carbon instance', function () {
        $entry = WaterEntry::factory()->create(['user_id' => $this->user->id]);

        expect($entry->date)->toBeInstanceOf(Carbon::class);
    });
});

describe('WaterEntry::getTodayIntake', function () {
    it('returns zero when no entries today', function () {
        WaterEntry::create(['user_id' => $this->user->id, 'glasses' => 5, 'date' => '2026-02-16']);

        $intake = WaterEntry::getTodayIntake($this->user);

        expect($intake)->toBe(0);
    });

    it('returns glasses for today entry', function () {
        WaterEntry::create(['user_id' => $this->user->id, 'glasses' => 4, 'date' => '2026-02-17']);

        $intake = WaterEntry::getTodayIntake($this->user);

        expect($intake)->toBe(4);
    });

    it('does not include other days', function () {
        WaterEntry::create(['user_id' => $this->user->id, 'glasses' => 10, 'date' => '2026-02-16']);
        WaterEntry::create(['user_id' => $this->user->id, 'glasses' => 2, 'date' => '2026-02-17']);

        $intake = WaterEntry::getTodayIntake($this->user);

        expect($intake)->toBe(2);
    });
});

describe('WaterEntry::getChartData', function () {
    it('returns correct structure', function () {
        $chart = WaterEntry::getChartData($this->user, 7);

        expect($chart)->toHaveKeys(['labels', 'data'])
            ->and($chart['labels'])->toHaveCount(7)
            ->and($chart['data'])->toHaveCount(7);
    });

    it('returns data for days with entries', function () {
        WaterEntry::create(['user_id' => $this->user->id, 'glasses' => 5, 'date' => '2026-02-17']);

        $chart = WaterEntry::getChartData($this->user, 1);

        // Today should have 5 glasses
        expect($chart['data'][0])->toBe(5);
    });

    it('fills days without entries with zero', function () {
        $chart = WaterEntry::getChartData($this->user, 3);

        // No entries exist
        expect($chart['data'][0])->toBe(0);
        expect($chart['data'][1])->toBe(0);
        expect($chart['data'][2])->toBe(0);
    });
});
