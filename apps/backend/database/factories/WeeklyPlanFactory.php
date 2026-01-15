<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'week_start' => now()->startOfWeek(),
            'focus_task_ids' => [],
            'note' => $this->faker->sentence,
        ];
    }
}
