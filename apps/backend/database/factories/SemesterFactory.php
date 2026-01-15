<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SemesterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Semester ' . $this->faker->randomDigitNotNull,
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
        ];
    }
}
