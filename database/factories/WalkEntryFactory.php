<?php

namespace Database\Factories;

use App\Models\WalkEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalkEntryFactory extends Factory
{
    protected $model = WalkEntry::class;

    public function definition(): array
    {
        return [
            'distance_miles' => $this->faker->randomFloat(2, 0.5, 5),
            'steps' => $this->faker->numberBetween(1000, 15000),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
