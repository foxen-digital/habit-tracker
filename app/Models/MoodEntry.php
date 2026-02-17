<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    use HasFactory;

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
     * Get mood data for the last 7 days (contiguous)
     */
    public static function getChartData(int $days = 7): array
    {
        $moodScores = [
            'great' => 5,
            'good' => 4,
            'okay' => 3,
            'bad' => 2,
            'terrible' => 1,
        ];

        $moodLabels = [
            'great' => '🌟',
            'good' => '😊',
            'okay' => '😐',
            'bad' => '😔',
            'terrible' => '😫',
        ];

        $entries = static::where('date', '>=', now()->subDays($days)->startOfDay())
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
