<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mood',
        'energy_level',
        'sleep_quality',
        'notes',
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
     * Get weekly mood trend for a user.
     */
    public static function getWeeklyMoodTrend(User $user): array
    {
        $entries = static::forUser($user)
            ->where('date', '>=', now()->subDays(7))
            ->get();

        $moodScores = [
            'great' => 5,
            'good' => 4,
            'okay' => 3,
            'bad' => 2,
            'terrible' => 1,
        ];

        $avgMood = $entries->map(fn ($e) => $moodScores[$e->mood] ?? 3)->avg();
        $avgEnergy = $entries->avg('energy_level');
        $avgSleep = $entries->whereNotNull('sleep_quality')->avg('sleep_quality');

        return [
            'average_mood' => round($avgMood ?? 3, 1),
            'average_energy' => round($avgEnergy ?? 5, 1),
            'average_sleep' => round($avgSleep ?? 5, 1),
            'entries_count' => $entries->count(),
        ];
    }

    /**
     * Get mood data for the last N days (contiguous) for a user.
     */
    public static function getChartData(User $user, int $days = 7): array
    {
        $moodScores = [
            'great' => 5,
            'good' => 4,
            'okay' => 3,
            'bad' => 2,
            'terrible' => 1,
        ];

        $entries = static::forUser($user)
            ->where('date', '>=', now()->subDays($days)->startOfDay())
            ->get()
            ->keyBy(fn ($e) => $e->date->format('Y-m-d'));

        $labels = [];
        $moodData = [];
        $energyData = [];
        $sleepData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $entry = $entries->get($dateKey);

            $labels[] = $date->format('M j');
            $moodData[] = $entry ? ($moodScores[$entry->mood] ?? 3) : null;
            $energyData[] = $entry?->energy_level;
            $sleepData[] = $entry?->sleep_quality;
        }

        return [
            'labels' => $labels,
            'mood' => $moodData,
            'energy' => $energyData,
            'sleep' => $sleepData,
        ];
    }
}
