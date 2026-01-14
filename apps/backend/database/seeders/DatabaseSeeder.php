<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\Course;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 Semester
        $semester = Semester::create([
            'name' => 'Semester 5 - 2024/2025',
            'start_date' => '2024-09-01',
            'end_date' => '2025-01-31',
        ]);

        // 2 Courses
        $course1 = Course::create([
            'semester_id' => $semester->id,
            'name' => 'Pemrograman Web',
        ]);

        $course2 = Course::create([
            'semester_id' => $semester->id,
            'name' => 'Basis Data',
        ]);

        // 3 Tasks with different statuses
        Task::create([
            'title' => 'Tugas UTS Pemrograman Web',
            'primary_course_id' => $course1->id,
            'due_date' => now()->addDays(7),
            'status' => 'Active',
            'progress' => 30,
        ]);

        Task::create([
            'title' => 'Praktikum Basis Data #3',
            'primary_course_id' => $course2->id,
            'due_date' => now()->addDays(3),
            'status' => 'Active',
            'progress' => 0,
        ]);

        Task::create([
            'title' => 'Essay Laravel - DONE',
            'primary_course_id' => $course1->id,
            'due_date' => now()->subDays(2),
            'status' => 'Done',
            'progress' => 100,
        ]);
    }
}
