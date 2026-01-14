<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Semester;
use App\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_cannot_be_created_without_primary_course_id_in_database(): void
    {
        $this->expectException(QueryException::class);

        Task::create([
            'title' => 'Test Task Without Course',
            // primary_course_id intentionally missing
            'status' => 'Active',
        ]);
    }

    public function test_task_can_be_created_with_valid_primary_course_id(): void
    {
        $semester = Semester::create([
            'name' => 'Test Semester',
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
        ]);

        $course = Course::create([
            'semester_id' => $semester->id,
            'name' => 'Test Course',
        ]);

        $task = Task::create([
            'title' => 'Test Task With Course',
            'primary_course_id' => $course->id,
            'status' => 'Active',
            'progress' => 0,
        ]);

        $this->assertTrue($task->exists);
        $this->assertEquals('Test Task With Course', $task->title);
        $this->assertEquals($course->id, $task->primary_course_id);
        $this->assertNotNull($task->primaryCourse);
    }

    public function test_task_status_defaults_to_Active(): void
    {
        $semester = Semester::create([
            'name' => 'Test Semester',
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
        ]);

        $course = Course::create([
            'semester_id' => $semester->id,
            'name' => 'Test Course',
        ]);

        $task = Task::create([
            'title' => 'Test Task Status Default',
            'primary_course_id' => $course->id,
        ]);

        $this->assertEquals('Active', $task->status);
        $this->assertEquals(0, $task->progress);
    }
}
