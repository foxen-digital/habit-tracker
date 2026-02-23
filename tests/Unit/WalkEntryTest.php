<?php

use App\Models\User;
use App\Models\WalkEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create();
});

describe('WalkEntry model', function () {
    it('can create a walk entry', function () {
        $entry = WalkEntry::factory()->create([
            'user_id' => $this->user->id,
            'distance_miles' => 2.5,
            'steps' => 5000,
            'date' => '2026-02-17',
            'notes' => 'Morning walk',
        ]);

        expect((float) $entry->distance_miles)->toEqual(2.5)
            ->and($entry->steps)->toBe(5000)
            ->and($entry->notes)->toBe('Morning walk');
    });

    it('casts distance to decimal with 2 places', function () {
        $entry = WalkEntry::create([
            'user_id' => $this->user->id,
            'distance_miles' => 2.5678,
            'steps' => 5000,
            'date' => '2026-02-17',
        ]);

        expect((float) $entry->distance_miles)->toEqual(2.57);
    });

    it('casts date to carbon instance', function () {
        $entry = WalkEntry::factory()->create(['user_id' => $this->user->id]);

        expect($entry->date)->toBeInstanceOf(Carbon::class);
    });

    it('allows null steps', function () {
        $entry = WalkEntry::create([
            'user_id' => $this->user->id,
            'distance_miles' => 1.5,
            'steps' => null,
            'date' => '2026-02-17',
        ]);

        expect($entry->steps)->toBeNull();
    });
});

describe('WalkEntry::getWeeklyStats', function () {
    it('returns zero stats when no entries exist', function () {
        $stats = WalkEntry::getWeeklyStats($this->user);

        expect($stats['total_miles'])->toBe(0.0)
            ->and($stats['average_miles'])->toBe(0.0)
            ->and($stats['entries_count'])->toBe(0);
    });

    it('calculates weekly stats correctly', function () {
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 2, 'steps' => 4000, 'date' => '2026-02-15']);
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 3, 'steps' => 6000, 'date' => '2026-02-16']);
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 1.5, 'steps' => 3000, 'date' => '2026-02-17']);

        $stats = WalkEntry::getWeeklyStats($this->user);

        expect($stats['total_miles'])->toBe(6.5)
            ->and($stats['average_miles'])->toBe(2.2)
            ->and($stats['entries_count'])->toBe(3);
    });

    it('only includes entries from last 7 days', function () {
        // 8 days ago - outside window
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 10, 'steps' => 20000, 'date' => '2026-02-09']);
        // Today - inside window
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 2, 'steps' => 4000, 'date' => '2026-02-17']);

        $stats = WalkEntry::getWeeklyStats($this->user);

        expect($stats['total_miles'])->toBe(2.0)
            ->and($stats['entries_count'])->toBe(1);
    });
});

describe('WalkEntry::getChartData', function () {
    it('returns correct structure for chart data', function () {
        WalkEntry::create(['user_id' => $this->user->id, 'distance_miles' => 2, 'steps' => 4000, 'date' => '2026-02-17']);

        $chart = WalkEntry::getChartData($this->user, 7);

        expect($chart)->toHaveKeys(['labels', 'distance', 'steps'])
            ->and($chart['labels'])->toHaveCount(7)
            ->and($chart['distance'])->toHaveCount(7)
            ->and($chart['steps'])->toHaveCount(7);
    });

    it('fills missing days with zero distance', function () {
        $chart = WalkEntry::getChartData($this->user, 3);

        expect($chart['distance'])->each->toBe(0);
    });

    it('fills missing days with null steps', function () {
        $chart = WalkEntry::getChartData($this->user, 3);

        expect($chart['steps'])->each->toBeNull();
    });
});
