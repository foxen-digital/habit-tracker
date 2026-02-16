<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return static::where('date', today())->value('glasses') ?? 0;
    }
}
