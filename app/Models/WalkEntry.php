<?php

namespace App\Models;

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
        
        return [
            'total_miles' => $entries->sum('distance_miles'),
            'average_miles' => round($entries->avg('distance_miles'), 2),
            'total_steps' => $entries->sum('steps'),
            'days_logged' => $entries->count(),
            'daily_goal' => 3,
        ];
    }
}
