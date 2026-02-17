<?php

namespace Database\Factories;

use App\Models\MoodEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoodEntryFactory extends Factory
{
    protected $model = MoodEntry::class;

    public function definition(): array
    {
        return [
            'mood' => $this->faker->randomElement(['great', 'good', 'okay', 'bad', 'terrible']),
            'energy_level' => $this->faker->numberBetween(1, 10),
            'sleep_quality' => $this->faker->optional()->numberBetween(1, 10),
            'notes' => $this->faker->optional()->sentence(),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ];
    }
}
