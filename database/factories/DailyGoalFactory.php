<?php

namespace Database\Factories;

use App\Models\DailyGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyGoalFactory extends Factory
{
    protected $model = DailyGoal::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->words(3, true),
            'emoji' => $this->faker->randomElement(['✅', '🎯', '💪', '📚', '🧘', '🏃']),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
