<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'emoji',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns this goal.
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

    public function completions(): HasMany
    {
        return $this->hasMany(DailyGoalCompletion::class);
    }

    public function getCompletionForDate(Carbon $date): ?DailyGoalCompletion
    {
        return $this->completions()->where('date', $date)->first();
    }

    public function isCompletedForDate(Carbon $date): bool
    {
        $completion = $this->getCompletionForDate($date);

        return $completion?->completed ?? false;
    }

    public function isCompletedToday(): bool
    {
        return $this->isCompletedForDate(Carbon::today());
    }

    public static function getActiveGoals(User $user): Collection
    {
        return static::forUser($user)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public static function getStatsForDate(User $user, Carbon $date): array
    {
        $goals = static::getActiveGoals($user);
        $completed = 0;

        foreach ($goals as $goal) {
            if ($goal->isCompletedForDate($date)) {
                $completed++;
            }
        }

        return [
            'total' => $goals->count(),
            'completed' => $completed,
            'percent' => $goals->count() > 0
                    ? round(($completed / $goals->count()) * 100)
                    : 0,
        ];
    }

    public static function getWeeklyStats(User $user): array
    {
        $stats = [];
        $goals = static::getActiveGoals($user);

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $completed = 0;

            foreach ($goals as $goal) {
                if ($goal->isCompletedForDate($date)) {
                    $completed++;
                }
            }

            $stats[] = [
                'date' => $date->format('D'),
                'completed' => $completed,
                'total' => $goals->count(),
                'percent' => $goals->count() > 0
                        ? round(($completed / $goals->count()) * 100)
                        : 0,
            ];
        }

        return $stats;
    }
}
