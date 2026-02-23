<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight_kg',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'weight_kg' => 'decimal:2',
    ];

    /**
     * Get the user that owns this entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query to a specific user.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Get goal progress for a user.
     */
    public static function getGoalProgress(User $user): array
    {
        $settings = $user->getSettings();
        $goalKg = $settings->weight_goal_kg;

        $latest = static::forUser($user)->orderBy('date', 'desc')->first();
        $first = static::forUser($user)->orderBy('date', 'asc')->first();

        if (! $latest || ! $first) {
            return ['current' => null, 'start' => null, 'lost' => 0, 'goal' => $goalKg];
        }

        $lost = $first->weight_kg - $latest->weight_kg;

        return [
            'current' => $latest->weight_kg,
            'start' => $first->weight_kg,
            'lost' => max(0, $lost),
            'goal' => $goalKg,
            'progress_percent' => $goalKg > 0 ? min(100, round((max(0, $lost) / $goalKg) * 100, 1)) : 0,
        ];
    }

    /**
     * Get weight data for the last N days (contiguous, with gaps filled) for a user.
     */
    public static function getChartData(User $user, int $days = 7): array
    {
        $entries = static::forUser($user)
            ->where('date', '>=', now()->subDays($days)->startOfDay())
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($e) => $e->date->format('Y-m-d'));

        $labels = [];
        $data = [];
        $lastWeight = null;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $entry = $entries->get($dateKey);

            $labels[] = $date->format('M j');

            if ($entry) {
                $data[] = $entry->weight_kg;
                $lastWeight = $entry->weight_kg;
            } else {
                // Use last known weight for continuity, or null if no data
                $data[] = $lastWeight;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
