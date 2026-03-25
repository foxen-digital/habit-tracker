<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaterEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaterEntryFactory extends Factory
{
    protected $model = WaterEntry::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'glasses' => $this->faker->numberBetween(0, 10),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ];
    }
}
