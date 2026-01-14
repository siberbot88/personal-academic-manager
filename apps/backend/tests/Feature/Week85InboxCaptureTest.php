<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\InboxItem;
use App\Models\Semester;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Week85InboxCaptureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup basic data
        User::factory()->create(['email' => 'test@example.com']);
        $semester = Semester::create(['name' => 'Test Semester', 'start_date' => now(), 'end_date' => now()->addMonths(6)]);
        Course::create(['name' => 'Test Course', 'semester_id' => $semester->id]);
    }

    public function test_inbox_item_requires_course_title_url()
    {
        // Missing required fields
        $item = new InboxItem([
            'title' => '',
            'url' => '',
        ]);

        $this->assertFalse($item->save());

        // Valid item
        $item = InboxItem::create([
            'course_id' => 1,
            'title' => 'Valid Title',
            'url' => 'https://example.com',
        ]);

        $this->assertDatabaseHas('inbox_items', [
            'title' => 'Valid Title',
            'url' => 'https://example.com',
        ]);
    }

    public function test_inbox_item_can_attach_to_task_optional()
    {
        $task = Task::create([
            'title' => 'Test Task',
            'primary_course_id' => 1,
            'status' => 'Active',
        ]);

        $item = InboxItem::create([
            'course_id' => 1,
            'task_id' => $task->id,
            'title' => 'Link to Task',
            'url' => 'https://example.com/task-material',
        ]);

        $this->assertEquals($task->id, $item->task_id);
        $this->assertInstanceOf(Task::class, $item->task);
    }

    public function test_inbox_item_tags_saved()
    {
        $item = InboxItem::create([
            'course_id' => 1,
            'title' => 'Tagged Item',
            'url' => 'https://example.com',
        ]);

        $item->attachTags(['skripsi', 'referensi', 'penting']);

        $this->assertCount(3, $item->tags);
        $this->assertTrue($item->tags->contains('name', 'skripsi'));
        $this->assertTrue($item->tags->contains('name', 'referensi'));
    }

    public function test_inbox_list_filters_by_course()
    {
        $semester = Semester::first();
        $course2 = Course::create(['name' => 'Course 2', 'semester_id' => $semester->id]);

        InboxItem::create([
            'course_id' => 1,
            'title' => 'Item for Course 1',
            'url' => 'https://example.com/1',
        ]);

        InboxItem::create([
            'course_id' => 1,
            'title' => 'Another for Course 1',
            'url' => 'https://example.com/2',
        ]);

        InboxItem::create([
            'course_id' => $course2->id,
            'title' => 'Item for Course 2',
            'url' => 'https://example.com/3',
        ]);

        $course1Items = InboxItem::where('course_id', 1)->count();
        $course2Items = InboxItem::where('course_id', $course2->id)->count();

        $this->assertEquals(2, $course1Items);
        $this->assertEquals(1, $course2Items);
    }
}
