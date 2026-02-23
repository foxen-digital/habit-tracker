<?php

namespace Database\Factories;

use App\Models\DailyGoal;
use App\Models\DailyGoalCompletion;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyGoalCompletionFactory extends Factory
{
    protected $model = DailyGoalCompletion::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'daily_goal_id' => DailyGoal::factory(),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'completed' => $this->faker->boolean(),
        ];
    }
}
