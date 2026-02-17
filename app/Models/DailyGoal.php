<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyGoal extends Model
{
    protected $fillable = [
        'name',
        'emoji',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function completions(): HasMany
    {
        return $this->hasMany(DailyGoalCompletion::class);
    }

    public function getCompletionForDate(Carbon $date): ?DailyGoalCompletion
    {
        return $this->completions()->where('date', $date->format('Y-m-d'))->first();
    }

    public function isCompletedForDate(Carbon $date): bool
    {
        $completion = $this->getCompletionForDate($date);

        return $completion?->completed ?? false;
    }

    public static function getActiveGoals(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public static function getStatsForDate(Carbon $date): array
    {
        $goals = static::getActiveGoals();
        $completed = 0;

        foreach ($goals as $goal) {
            if ($goal->isCompletedForDate($date)) {
                $completed++;
            }
        }

        return [
            'total' => $goals->count(),
            'completed' => $completed,
            'percent' => $goals->count() > 0 ? round(($completed / $goals->count()) * 100) : 0,
        ];
    }

    public static function getWeeklyStats(): array
    {
        $stats = [];
        $goals = static::getActiveGoals();

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
                'percent' => $goals->count() > 0 ? round(($completed / $goals->count()) * 100) : 0,
            ];
        }

        return $stats;
    }
}
