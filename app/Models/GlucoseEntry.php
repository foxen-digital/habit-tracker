<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GlucoseEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'glucose_mmol_l',
        'reading_type',
        'measured_at',
        'notes',
    ];

    protected $casts = [
        'glucose_mmol_l' => 'decimal:2',
        'measured_at' => 'datetime',
    ];

    public const READING_TYPES = [
        'fasting' => 'Fasting',
        'pre_meal' => 'Pre-meal',
        'post_meal' => 'Post-meal',
        'bedtime' => 'Bedtime',
        'other' => 'Other',
    ];

    // UK target ranges (mmol/L)
    public const TARGET_RANGES = [
        'fasting' => ['min' => 4.0, 'max' => 7.0],
        'pre_meal' => ['min' => 4.0, 'max' => 7.0],
        'post_meal' => ['min' => 4.0, 'max' => 8.5],
        'bedtime' => ['min' => 4.0, 'max' => 8.0],
        'other' => ['min' => 4.0, 'max' => 11.0],
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
     * Check if reading is within target range
     */
    public function isInTargetRange(): bool
    {
        $range = self::TARGET_RANGES[$this->reading_type] ?? self::TARGET_RANGES['other'];

        return $this->glucose_mmol_l >= $range['min'] && $this->glucose_mmol_l <= $range['max'];
    }

    /**
     * Get entries within a date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('measured_at', [$startDate, $endDate]);
    }

    /**
     * Get weekly statistics for a user.
     */
    public static function getWeeklyStats(User $user): array
    {
        $now = now();
        $last7Days = static::forUser($user)
            ->whereBetween('measured_at', [$now->copy()->subDays(7), $now])
            ->orderBy('measured_at', 'desc')
            ->get();

        if ($last7Days->isEmpty()) {
            return [
                'average' => null,
                'readings' => 0,
                'in_target' => 0,
                'in_target_percent' => 0,
            ];
        }

        $inTarget = $last7Days->filter(fn ($e) => $e->isInTargetRange())->count();

        return [
            'average' => round($last7Days->avg('glucose_mmol_l'), 2),
            'readings' => $last7Days->count(),
            'in_target' => $inTarget,
            'in_target_percent' => round(($inTarget / $last7Days->count()) * 100),
        ];
    }

    /**
     * Get glucose data for the last N days (grouped by day) for a user.
     */
    public static function getChartData(User $user, int $days = 7): array
    {
        $entries = static::forUser($user)
            ->where('measured_at', '>=', now()->subDays($days)->startOfDay())
            ->orderBy('measured_at')
            ->get()
            ->groupBy(fn ($e) => $e->measured_at->format('Y-m-d'));

        $labels = [];
        $avgData = [];
        $fastingData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $dayEntries = $entries->get($dateKey);

            $labels[] = $date->format('M j');

            if ($dayEntries && $dayEntries->count() > 0) {
                $avgData[] = round($dayEntries->avg('glucose_mmol_l'), 1);
                $fastingEntry = $dayEntries->firstWhere('reading_type', 'fasting');
                $fastingData[] = $fastingEntry?->glucose_mmol_l;
            } else {
                $avgData[] = null;
                $fastingData[] = null;
            }
        }

        return [
            'labels' => $labels,
            'average' => $avgData,
            'fasting' => $fastingData,
        ];
    }
}
