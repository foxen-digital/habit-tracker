<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalkEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distance_miles',
        'steps',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'distance_miles' => 'decimal:2',
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
     * Get weekly stats for a user.
     */
    public static function getWeeklyStats(User $user): array
    {
        $entries = static::forUser($user)
            ->where('date', '>=', now()->subDays(7))
            ->get();

        $totalMiles = $entries->sum('distance_miles');
        $avgMiles = $entries->avg('distance_miles');

        return [
            'total_miles' => round($totalMiles, 1),
            'average_miles' => round($avgMiles ?? 0, 1),
            'entries_count' => $entries->count(),
        ];
    }

    /**
     * Get walking data for the last N days (contiguous) for a user.
     */
    public static function getChartData(User $user, int $days = 7): array
    {
        $entries = static::forUser($user)
            ->where('date', '>=', now()->subDays($days)->startOfDay())
            ->get()
            ->keyBy(fn ($e) => $e->date->format('Y-m-d'));

        $labels = [];
        $data = [];
        $stepsData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $entry = $entries->get($dateKey);

            $labels[] = $date->format('M j');
            $data[] = $entry?->distance_miles ?? 0;
            $stepsData[] = $entry?->steps;
        }

        return [
            'labels' => $labels,
            'distance' => $data,
            'steps' => $stepsData,
        ];
    }
}
