<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeightEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightEntryFactory extends Factory
{
    protected $model = WeightEntry::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'weight_kg' => $this->faker->randomFloat(2, 60, 120),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
