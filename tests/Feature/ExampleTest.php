<?php

use App\Models\User;

it('returns a successful response', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
});
