<?php

use App\Models\MoodEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
});

describe('MoodEntry model', function () {
    it('can create a mood entry', function () {
        $entry = MoodEntry::factory()->create([
            'mood' => 'good',
            'energy_level' => 7,
            'sleep_quality' => 8,
            'notes' => 'Feeling rested',
            'date' => '2026-02-17',
        ]);

        expect($entry->mood)->toBe('good')
            ->and($entry->energy_level)->toBe(7)
            ->and($entry->sleep_quality)->toBe(8)
            ->and($entry->notes)->toBe('Feeling rested');
    });

    it('casts date to carbon instance', function () {
        $entry = MoodEntry::factory()->create();

        expect($entry->date)->toBeInstanceOf(Carbon::class);
    });

    it('allows null sleep quality', function () {
        $entry = MoodEntry::create([
            'mood' => 'okay',
            'energy_level' => 5,
            'sleep_quality' => null,
            'date' => '2026-02-17',
        ]);

        expect($entry->sleep_quality)->toBeNull();
    });
});

describe('MoodEntry::getWeeklyMoodTrend', function () {
    it('returns defaults when no entries exist', function () {
        $trend = MoodEntry::getWeeklyMoodTrend();

        expect($trend['average_mood'])->toBe(3.0)
            ->and($trend['average_energy'])->toBe(5.0)
            ->and($trend['average_sleep'])->toBe(5.0)
            ->and($trend['entries_count'])->toBe(0);
    });

    it('calculates mood average correctly', function () {
        MoodEntry::create(['mood' => 'great', 'energy_level' => 8, 'date' => '2026-02-15']);
        MoodEntry::create(['mood' => 'okay', 'energy_level' => 5, 'date' => '2026-02-16']);

        $trend = MoodEntry::getWeeklyMoodTrend();

        // great=5, okay=3, avg=4
        expect($trend['average_mood'])->toBe(4.0)
            ->and($trend['average_energy'])->toBe(6.5)
            ->and($trend['entries_count'])->toBe(2);
    });

    it('only includes entries from last 7 days', function () {
        MoodEntry::create(['mood' => 'terrible', 'energy_level' => 1, 'date' => '2026-02-09']);
        MoodEntry::create(['mood' => 'great', 'energy_level' => 10, 'date' => '2026-02-17']);

        $trend = MoodEntry::getWeeklyMoodTrend();

        expect($trend['entries_count'])->toBe(1)
            ->and($trend['average_mood'])->toBe(5.0);
    });
});

describe('MoodEntry::getChartData', function () {
    it('returns correct structure', function () {
        $chart = MoodEntry::getChartData(7);

        expect($chart)->toHaveKeys(['labels', 'mood', 'energy', 'sleep'])
            ->and($chart['labels'])->toHaveCount(7)
            ->and($chart['mood'])->toHaveCount(7)
            ->and($chart['energy'])->toHaveCount(7)
            ->and($chart['sleep'])->toHaveCount(7);
    });

    it('converts mood strings to numeric values', function () {
        MoodEntry::create(['mood' => 'great', 'energy_level' => 10, 'date' => '2026-02-17']);

        $chart = MoodEntry::getChartData(1);

        // great = 5
        expect($chart['mood'][0])->toBe(5);
    });

    it('returns null for missing days', function () {
        $chart = MoodEntry::getChartData(3);

        expect($chart['mood'])->each->toBeNull()
            ->and($chart['energy'])->each->toBeNull()
            ->and($chart['sleep'])->each->toBeNull();
    });
});
