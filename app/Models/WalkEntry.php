<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WalkEntry extends Model
{
    protected $fillable = [
        'distance_miles',
        'steps',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'distance_miles' => 'decimal:2',
    ];

    public static function getWeeklyStats(): array
    {
        $entries = static::where('date', '>=', now()->subDays(7))->get();

        $totalMiles = $entries->sum('distance_miles');
        $avgMiles = $entries->avg('distance_miles');

        return [
            'total_miles' => round($totalMiles, 1),
            'average_miles' => round($avgMiles ?? 0, 1),
            'entries_count' => $entries->count(),
        ];
    }

    /**
     * Get walking data for the last N days (contiguous)
     */
    public static function getChartData(int $days = 7): array
    {
        $entries = static::where('date', '>=', now()->subDays($days)->startOfDay())
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
