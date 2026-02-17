<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WaterEntry extends Model
{
    protected $fillable = [
        'glasses',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function getTodayIntake(): int
    {
        return static::where('date', today())->sum('glasses');
    }

    /**
     * Get water intake data for the last N days (contiguous)
     */
    public static function getChartData(int $days = 7): array
    {
        $entries = static::where('date', '>=', now()->subDays($days)->startOfDay())
            ->get()
            ->groupBy(fn($e) => $e->date->format('Y-m-d'))
            ->map(fn($group) => $group->sum('glasses'));

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
