<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeightEntry extends Model
{
    protected $fillable = [
        'weight_kg',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'weight_kg' => 'decimal:2',
    ];

    // Danny's starting weight goal: lose 25kg
    // Target walking: 3 miles/day
    public static function getGoalProgress(): array
    {
        $latest = static::orderBy('date', 'desc')->first();
        $first = static::orderBy('date', 'asc')->first();
        
        if (!$latest || !$first) {
            return ['current' => null, 'start' => null, 'lost' => 0, 'goal' => 25];
        }
        
        $lost = $first->weight_kg - $latest->weight_kg;
        
        return [
            'current' => $latest->weight_kg,
            'start' => $first->weight_kg,
            'lost' => max(0, $lost),
            'goal' => 25,
            'progress_percent' => min(100, round((max(0, $lost) / 25) * 100, 1)),
        ];
    }

    /**
     * Get weight data for the last N days (contiguous, with gaps filled)
     */
    public static function getChartData(int $days = 7): array
    {
        $entries = static::where('date', '>=', now()->subDays($days)->startOfDay())
            ->orderBy('date')
            ->get()
            ->keyBy(fn($e) => $e->date->format('Y-m-d'));

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
