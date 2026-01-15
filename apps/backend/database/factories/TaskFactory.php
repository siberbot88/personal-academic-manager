<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Semester;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'primary_course_id' => Course::factory(), // This might recurse if CourseFactory not there
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => 'Active',
            'progress' => 0,
            'size' => 'M',
            'priority_boost' => false,
            'health_score' => 100,
            'health_status' => 'aman',
            'first_touched_at' => null,
            'started_lead_days' => null,
        ];
    }
}
