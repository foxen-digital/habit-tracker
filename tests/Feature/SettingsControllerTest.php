<?php

use App\Models\User;
use App\Models\UserSettings;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-02-17');
    $this->user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $this->actingAs($this->user);
});

describe('SettingsController::index', function () {
    it('displays the settings page', function () {
        $response = $this->get('/settings');

        $response->assertOk()
            ->assertViewIs('settings.index')
            ->assertViewHas('settings');
    });

    it('creates default settings for user without settings', function () {
        expect($this->user->settings)->toBeNull();

        $response = $this->get('/settings');

        $response->assertOk();
        $this->user->refresh();
        expect($this->user->settings)->not->toBeNull()
            ->and($this->user->settings->weight_goal_kg)->toBe('25.00')
            ->and($this->user->settings->daily_walk_target_miles)->toBe('3.00')
            ->and($this->user->settings->daily_water_target_glasses)->toBe(8)
            ->and($this->user->settings->weight_unit)->toBe('kg')
            ->and($this->user->settings->distance_unit)->toBe('miles');
    });

    it('displays existing user settings', function () {
        $settings = UserSettings::create([
            'user_id' => $this->user->id,
            'weight_goal_kg' => 80.5,
            'daily_walk_target_miles' => 5.0,
            'daily_water_target_glasses' => 10,
            'weight_unit' => 'lbs',
            'distance_unit' => 'km',
        ]);

        $response = $this->get('/settings');

        $response->assertOk()
            ->assertViewHas('settings', function ($viewSettings) use ($settings) {
                return $viewSettings->id === $settings->id;
            });
    });
});

describe('SettingsController::update', function () {
    it('updates all settings and redirects', function () {
        $settings = $this->user->getSettings();

        $response = $this->post('/settings', [
            'weight_goal_kg' => 75.5,
            'daily_walk_target_miles' => 4.5,
            'daily_water_target_glasses' => 10,
            'weight_unit' => 'lbs',
            'distance_unit' => 'km',
        ]);

        $response->assertRedirect('/settings')
            ->assertSessionHas('success', 'Settings updated!');

        $settings->refresh();
        expect($settings->weight_goal_kg)->toBe('75.50')
            ->and($settings->daily_walk_target_miles)->toBe('4.50')
            ->and($settings->daily_water_target_glasses)->toBe(10)
            ->and($settings->weight_unit)->toBe('lbs')
            ->and($settings->distance_unit)->toBe('km');
    });

    it('updates only weight_goal_kg', function () {
        $settings = $this->user->getSettings();

        $response = $this->post('/settings', [
            'weight_goal_kg' => 90.0,
            'daily_walk_target_miles' => $settings->daily_walk_target_miles,
            'daily_water_target_glasses' => $settings->daily_water_target_glasses,
            'weight_unit' => $settings->weight_unit,
            'distance_unit' => $settings->distance_unit,
        ]);

        $response->assertSessionHasNoErrors();
        expect($settings->fresh()->weight_goal_kg)->toBe('90.00');
    });
});

describe('SettingsController validation - weight_goal_kg', function () {
    it('validates weight_goal_kg is required', function () {
        $this->post('/settings', [
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['weight_goal_kg']);
    });

    it('validates weight_goal_kg is numeric', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 'not-a-number',
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['weight_goal_kg']);
    });

    it('validates weight_goal_kg minimum (1)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 0.5,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['weight_goal_kg']);
    });

    it('validates weight_goal_kg maximum (200)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 250,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['weight_goal_kg']);
    });

    it('accepts weight_goal_kg at minimum boundary', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 1,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });

    it('accepts weight_goal_kg at maximum boundary', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 200,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });
});

describe('SettingsController validation - daily_walk_target_miles', function () {
    it('validates daily_walk_target_miles is required', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_walk_target_miles']);
    });

    it('validates daily_walk_target_miles is numeric', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 'not-a-number',
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_walk_target_miles']);
    });

    it('validates daily_walk_target_miles minimum (0)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => -1,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_walk_target_miles']);
    });

    it('validates daily_walk_target_miles maximum (50)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 60,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_walk_target_miles']);
    });

    it('accepts daily_walk_target_miles at minimum boundary (0)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });

    it('accepts daily_walk_target_miles at maximum boundary (50)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 50,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });
});

describe('SettingsController validation - daily_water_target_glasses', function () {
    it('validates daily_water_target_glasses is required', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_water_target_glasses']);
    });

    it('validates daily_water_target_glasses is integer', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 5.5,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_water_target_glasses']);
    });

    it('validates daily_water_target_glasses minimum (1)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 0,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_water_target_glasses']);
    });

    it('validates daily_water_target_glasses maximum (20)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 25,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['daily_water_target_glasses']);
    });

    it('accepts daily_water_target_glasses at minimum boundary (1)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 1,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });

    it('accepts daily_water_target_glasses at maximum boundary (20)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 20,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });
});

describe('SettingsController validation - weight_unit', function () {
    it('validates weight_unit is required', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['weight_unit']);
    });

    it('validates weight_unit is valid option (kg)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });

    it('validates weight_unit is valid option (lbs)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'lbs',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });

    it('rejects invalid weight_unit', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'stones',
            'distance_unit' => 'miles',
        ])->assertSessionHasErrors(['weight_unit']);
    });
});

describe('SettingsController validation - distance_unit', function () {
    it('validates distance_unit is required', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
        ])->assertSessionHasErrors(['distance_unit']);
    });

    it('validates distance_unit is valid option (miles)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();
    });

    it('validates distance_unit is valid option (km)', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'km',
        ])->assertSessionHasNoErrors();
    });

    it('rejects invalid distance_unit', function () {
        $this->post('/settings', [
            'weight_goal_kg' => 80,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'meters',
        ])->assertSessionHasErrors(['distance_unit']);
    });
});

describe('SettingsController - user isolation', function () {
    it('user can only access their own settings', function () {
        $otherUser = User::factory()->create();
        $otherSettings = UserSettings::create([
            'user_id' => $otherUser->id,
            'weight_goal_kg' => 100,
            'daily_walk_target_miles' => 5.0,
            'daily_water_target_glasses' => 12,
            'weight_unit' => 'lbs',
            'distance_unit' => 'km',
        ]);

        // Authenticated user can only see their own settings
        $response = $this->get('/settings');

        $response->assertOk()
            ->assertViewHas('settings', function ($settings) use ($otherSettings) {
                return $settings->id !== $otherSettings->id;
            });
    });

    it('user can only update their own settings', function () {
        $otherUser = User::factory()->create();
        $otherSettings = UserSettings::create([
            'user_id' => $otherUser->id,
            'weight_goal_kg' => 100,
            'daily_walk_target_miles' => 5.0,
            'daily_water_target_glasses' => 12,
            'weight_unit' => 'lbs',
            'distance_unit' => 'km',
        ]);

        // Authenticated user updates settings - should only affect their own
        $this->post('/settings', [
            'weight_goal_kg' => 75,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ])->assertSessionHasNoErrors();

        // Other user's settings should remain unchanged
        $otherSettings->refresh();
        expect($otherSettings->weight_goal_kg)->toBe('100.00')
            ->and($otherSettings->weight_unit)->toBe('lbs');
    });
});

describe('SettingsController - authentication', function () {
    it('redirects unauthenticated user from settings index', function () {
        auth()->logout();

        $response = $this->get('/settings');

        $response->assertRedirect('/login');
    });

    it('redirects unauthenticated user from settings update', function () {
        auth()->logout();

        $response = $this->post('/settings', [
            'weight_goal_kg' => 75,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ]);

        $response->assertRedirect('/login');
    });
});
