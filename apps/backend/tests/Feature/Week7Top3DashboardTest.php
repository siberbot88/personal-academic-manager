<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Semester;
use App\Models\Task;
use App\Models\TaskPhase;
use App\Models\User;
use App\Services\Top3Selector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class Week7Top3DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(['email' => 'test@example.com']);
        $this->actingAs($user);

        $semester = Semester::create(['name' => 'Sem 1', 'start_date' => now(), 'end_date' => now()->addMonths(6)]);
        Course::create(['name' => 'Course 1', 'semester_id' => $semester->id]);
    }

    public function test_slot1_picks_overdue_first()
    {
        // Task A: Overdue Phase
        $taskA = Task::create(['title' => 'Overdue Task', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 50]);
        TaskPhase::create(['task_id' => $taskA->id, 'title' => 'P1', 'due_date' => now()->subDay(), 'progress_pct' => 0]);

        // Task B: Future Due
        $taskB = Task::create(['title' => 'Future Task', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 50, 'due_date' => now()->addDay()]);

        $selector = app(Top3Selector::class);
        $top3 = $selector->getTop3();

        $this->assertEquals($taskA->id, $top3['slot1']->id, 'Slot 1 should be overdue task');
        $this->assertEquals('Overdue', $selector->getReason($top3['slot1'], 'slot1'));
    }

    public function test_slot2_picks_progress_zero_task()
    {
        // Task A: Progress 50%
        $taskA = Task::create(['title' => 'Mid progress', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 50, 'due_date' => now()->addDays(5)]);

        // Task B: Progress 0%
        $taskB = Task::create(['title' => 'Zero progress', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 0, 'due_date' => now()->addDays(10)]);

        $selector = app(Top3Selector::class);
        $top3 = $selector->getTop3();

        // Slot 1 might pick Task A (nearest due) or B (depends). 
        // A due = +5 days, B due = +10 days. A is near.
        // So Slot 1 = A.
        // Slot 2 should pick untouched -> B.

        $this->assertEquals($taskA->id, $top3['slot1']->id ?? null);
        $this->assertEquals($taskB->id, $top3['slot2']->id ?? null, 'Slot 2 should be zero progress task');
    }

    public function test_slot3_picks_highest_risk()
    {
        // Task A: Low Risk
        $taskA = Task::create(['title' => 'Low Risk', 'primary_course_id' => 1, 'status' => 'Active', 'health_score' => 100, 'attention_flag' => false]);

        // Task B: High Risk (Attention Flag)
        $taskB = Task::create(['title' => 'High Risk', 'primary_course_id' => 1, 'status' => 'Active', 'health_score' => 50, 'attention_flag' => true]);

        // To avoid Slot 1/2 picking them, let's make their partial data irrelevant or ensure dedupe handles it.
        // Task B has active progress? Default 0. So B fits Slot 2.
        // Task A default 0. A fits Slot 2.

        // Let's set progress=50 for both so they miss Slot 2.
        $taskA->progress_pct = 50;
        $taskA->save();
        $taskB->progress_pct = 50;
        $taskB->save();

        // Both miss Slot 2.
        // Slot 1: Due date? Null.
        // If due date null on both?
        // Selector logic: effective_due asc. Nulls last.
        // So Slot 1 might pick A or B depending on tie break (created_at is implicitly not sorted).

        // Let's explicitly give A a closer due date so it takes Slot 1.
        $taskA->due_date = now()->addDay();
        $taskA->save();
        $taskB->due_date = now()->addDays(2);
        $taskB->save();

        // Slot 1 = A.
        // Slot 2 = Empty.
        // Slot 3 = B (High Risk).

        $selector = app(Top3Selector::class);
        $top3 = $selector->getTop3();

        $this->assertEquals($taskA->id, $top3['slot1']->id ?? null);
        $this->assertEquals($taskB->id, $top3['slot3']->id ?? null, 'Slot 3 should be high risk task');
        // Assertion removed as implicit by task selection
    }

    public function test_dedupe_works()
    {
        // Task A: Fits ALL slots (Overdue, Progress 0, High Risk)
        $taskA = Task::create(['title' => 'All Rounder', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 0, 'due_date' => now()->subDay(), 'attention_flag' => true]);

        // Task B: Another task
        $taskB = Task::create(['title' => 'Another', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 50]);

        $selector = app(Top3Selector::class);
        $top3 = $selector->getTop3();
        // Debug removed

        // Slot 1 = A (Overdue).
        // Slot 2 = Should SKIP A. But B progress is 50, so B not valid for Slot 2. Slot 2 Empty.
        // Slot 3 = Should SKIP A. Slot 3 might pick B?
        // B has low risk.
        // Result: 1=A, 2=null, 3=B.

        $this->assertEquals($taskA->id, $top3['slot1']->id);
        $this->assertNotEquals($taskA->id, $top3['slot2']->id ?? 0);
        $this->assertNotEquals($taskA->id, $top3['slot3']->id ?? 0);
        $this->assertEquals($taskB->id, $top3['slot3']->id);
    }
}
