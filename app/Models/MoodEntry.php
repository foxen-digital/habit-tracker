<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    protected $fillable = [
        'mood',
        'energy_level',
        'sleep_quality',
        'notes',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function getWeeklyMoodTrend(): array
    {
        $entries = static::where('date', '>=', now()->subDays(7))->get();
        
        $moodScores = [
            'great' => 5,
            'good' => 4,
            'okay' => 3,
            'bad' => 2,
            'terrible' => 1,
        ];
        
        $avgMood = $entries->map(fn($e) => $moodScores[$e->mood] ?? 3)->avg();
        $avgEnergy = $entries->avg('energy_level');
        $avgSleep = $entries->whereNotNull('sleep_quality')->avg('sleep_quality');
        
        return [
            'average_mood' => round($avgMood ?? 3, 1),
            'average_energy' => round($avgEnergy ?? 5, 1),
            'average_sleep' => round($avgSleep ?? 5, 1),
            'entries_count' => $entries->count(),
        ];
    }
}
