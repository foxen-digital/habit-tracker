<?php

use App\Models\WaterEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
});

describe('WaterEntry model', function () {
    it('can create a water entry', function () {
        $entry = WaterEntry::factory()->create([
            'glasses' => 6,
            'date' => '2026-02-17',
        ]);

        expect($entry->glasses)->toBe(6)
            ->and($entry->date->format('Y-m-d'))->toBe('2026-02-17');
    });

    it('casts date to carbon instance', function () {
        $entry = WaterEntry::factory()->create();

        expect($entry->date)->toBeInstanceOf(Carbon::class);
    });
});

describe('WaterEntry::getTodayIntake', function () {
    it('returns zero when no entries today', function () {
        WaterEntry::create(['glasses' => 5, 'date' => '2026-02-16']);

        $intake = WaterEntry::getTodayIntake();

        expect($intake)->toBe(0);
    });

    it('returns glasses for today entry', function () {
        WaterEntry::create(['glasses' => 4, 'date' => '2026-02-17']);

        $intake = WaterEntry::getTodayIntake();

        expect($intake)->toBe(4);
    });

    it('does not include other days', function () {
        WaterEntry::create(['glasses' => 10, 'date' => '2026-02-16']);
        WaterEntry::create(['glasses' => 2, 'date' => '2026-02-17']);

        $intake = WaterEntry::getTodayIntake();

        expect($intake)->toBe(2);
    });
});

describe('WaterEntry::getChartData', function () {
    it('returns correct structure', function () {
        $chart = WaterEntry::getChartData(7);

        expect($chart)->toHaveKeys(['labels', 'data'])
            ->and($chart['labels'])->toHaveCount(7)
            ->and($chart['data'])->toHaveCount(7);
    });

    it('returns data for days with entries', function () {
        WaterEntry::create(['glasses' => 5, 'date' => '2026-02-17']);

        $chart = WaterEntry::getChartData(1);

        // Today should have 5 glasses
        expect($chart['data'][0])->toBe(5);
    });

    it('fills days without entries with zero', function () {
        $chart = WaterEntry::getChartData(3);

        // No entries exist
        expect($chart['data'][0])->toBe(0);
        expect($chart['data'][1])->toBe(0);
        expect($chart['data'][2])->toBe(0);
    });
});
