<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'glasses',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
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
     * Get today's intake for a user.
     */
    public static function getTodayIntake(User $user): int
    {
        return static::forUser($user)->where('date', today())->sum('glasses');
    }

    /**
     * Get water intake data for the last N days (contiguous) for a user.
     */
    public static function getChartData(User $user, int $days = 7): array
    {
        $entries = static::forUser($user)
            ->where('date', '>=', now()->subDays($days)->startOfDay())
            ->get()
            ->groupBy(fn ($e) => $e->date->format('Y-m-d'))
            ->map(fn ($group) => $group->sum('glasses'));

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');

            $labels[] = $date->format('M j');
            $data[] = $entries->get($dateKey, 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
