<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'weight_goal_kg',
        'daily_walk_target_miles',
        'daily_water_target_glasses',
        'weight_unit',
        'distance_unit',
    ];

    protected $casts = [
        'weight_goal_kg' => 'decimal:2',
        'daily_walk_target_miles' => 'decimal:2',
        'daily_water_target_glasses' => 'integer',
    ];

    /**
     * Get the user that owns these settings.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get default settings for a new user.
     */
    public static function getDefaults(): array
    {
        return [
            'weight_goal_kg' => 25,
            'daily_walk_target_miles' => 3.0,
            'daily_water_target_glasses' => 8,
            'weight_unit' => 'kg',
            'distance_unit' => 'miles',
        ];
    }
}
