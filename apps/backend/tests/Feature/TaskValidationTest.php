<?php

use App\Models\Course;
use App\Models\Semester;
use App\Models\Task;
use App\Models\User;

test('task cannot be created without primary_course_id in database', function () {
    expect(function () {
        Task::create([
            'title' => 'Test Task Without Course',
            // primary_course_id intentionally missing
            'status' => 'Active',
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

test('task can be created with valid primary_course_id', function () {
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

    expect($task->exists)->toBeTrue();
    expect($task->title)->toBe('Test Task With Course');
    expect($task->primary_course_id)->toBe($course->id);
    expect($task->primaryCourse)->not->toBeNull();
});

test('task status defaults to Active', function () {
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

    expect($task->status)->toBe('Active');
    expect($task->progress)->toBe(0);
});
