<?php

use App\Models\MoodEntry;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\WeightEntry;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
});

describe('EntryController::storeWeight', function () {
    it('creates a weight entry and redirects', function () {
        $response = $this->post('/weight', [
            'weight_kg' => 85.5,
            'date' => '2026-02-17',
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Weight entry saved!');

        expect(WeightEntry::count())->toBe(1)
            ->and((float) WeightEntry::first()->weight_kg)->toEqual(85.5);
    });

    it('validates weight is required', function () {
        $this->post('/weight', ['date' => '2026-02-17'])
            ->assertSessionHasErrors(['weight_kg']);
    });

    it('validates weight is numeric', function () {
        $this->post('/weight', [
            'weight_kg' => 'not-a-number',
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['weight_kg']);
    });

    it('validates weight range (min 50)', function () {
        $this->post('/weight', [
            'weight_kg' => 40,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['weight_kg']);
    });

    it('validates weight range (max 300)', function () {
        $this->post('/weight', [
            'weight_kg' => 350,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['weight_kg']);
    });

    it('validates date is required', function () {
        $this->post('/weight', ['weight_kg' => 85])
            ->assertSessionHasErrors(['date']);
    });

    it('validates date is not in future', function () {
        $this->post('/weight', [
            'weight_kg' => 85,
            'date' => '2026-02-18',
        ])->assertSessionHasErrors(['date']);
    });

    it('allows optional notes', function () {
        $this->post('/weight', [
            'weight_kg' => 85,
            'date' => '2026-02-17',
            'notes' => 'Feeling good today',
        ])->assertSessionHasNoErrors();

        expect(WeightEntry::first()->notes)->toBe('Feeling good today');
    });
});

describe('EntryController::storeWalk', function () {
    it('creates a walk entry and redirects', function () {
        $response = $this->post('/walk', [
            'distance_miles' => 2.5,
            'steps' => 5000,
            'date' => '2026-02-17',
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Walk entry saved!');

        expect(WalkEntry::count())->toBe(1)
            ->and((float) WalkEntry::first()->distance_miles)->toEqual(2.5)
            ->and(WalkEntry::first()->steps)->toBe(5000);
    });

    it('validates distance is required', function () {
        $this->post('/walk', ['date' => '2026-02-17'])
            ->assertSessionHasErrors(['distance_miles']);
    });

    it('validates distance range', function () {
        $this->post('/walk', [
            'distance_miles' => 100,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['distance_miles']);
    });

    it('validates steps range', function () {
        $this->post('/walk', [
            'distance_miles' => 1,
            'steps' => 200000,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['steps']);
    });

    it('allows null steps', function () {
        $this->post('/walk', [
            'distance_miles' => 1,
            'steps' => null,
            'date' => '2026-02-17',
        ])->assertSessionHasNoErrors();

        expect(WalkEntry::first()->steps)->toBeNull();
    });
});

describe('EntryController::storeWater', function () {
    it('creates a water entry and redirects', function () {
        $response = $this->post('/water', [
            'glasses' => 6,
            'date' => '2026-02-17',
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Water entry saved!');

        expect(WaterEntry::count())->toBe(1)
            ->and(WaterEntry::first()->glasses)->toBe(6);
    });

    it('validates glasses is required', function () {
        $this->post('/water', ['date' => '2026-02-17'])
            ->assertSessionHasErrors(['glasses']);
    });

    it('validates glasses range', function () {
        $this->post('/water', [
            'glasses' => 25,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['glasses']);
    });
});

describe('EntryController::storeMood', function () {
    it('creates a mood entry and redirects', function () {
        $response = $this->post('/mood', [
            'mood' => 'good',
            'energy_level' => 7,
            'sleep_quality' => 8,
            'date' => '2026-02-17',
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Mood entry saved!');

        expect(MoodEntry::count())->toBe(1)
            ->and(MoodEntry::first()->mood)->toBe('good')
            ->and(MoodEntry::first()->energy_level)->toBe(7)
            ->and(MoodEntry::first()->sleep_quality)->toBe(8);
    });

    it('validates mood is valid option', function () {
        $this->post('/mood', [
            'mood' => 'amazing',
            'energy_level' => 7,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['mood']);
    });

    it('validates energy level range', function () {
        $this->post('/mood', [
            'mood' => 'good',
            'energy_level' => 15,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['energy_level']);
    });

    it('validates sleep quality range', function () {
        $this->post('/mood', [
            'mood' => 'good',
            'energy_level' => 7,
            'sleep_quality' => 15,
            'date' => '2026-02-17',
        ])->assertSessionHasErrors(['sleep_quality']);
    });

    it('allows null sleep quality', function () {
        $this->post('/mood', [
            'mood' => 'good',
            'energy_level' => 7,
            'sleep_quality' => null,
            'date' => '2026-02-17',
        ])->assertSessionHasNoErrors();
    });
});
