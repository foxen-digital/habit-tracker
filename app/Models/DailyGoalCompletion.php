<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyGoalCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_goal_id',
        'date',
        'completed',
    ];

    protected $casts = [
        'date' => 'date',
        'completed' => 'boolean',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(DailyGoal::class, 'daily_goal_id');
    }
}
