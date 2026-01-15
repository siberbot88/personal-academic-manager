<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Course;
use App\Models\StudySession;
use App\Models\WeeklyPlan;
use App\Services\StudyStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Livewire\Livewire;
use App\Filament\Widgets\StudyQuickLogWidget;
use App\Filament\Pages\WeeklyReviewPage;

class Week11BehaviorTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function first_touched_at_set_once_only()
    {
        $task = Task::factory()->create(['first_touched_at' => null]);

        $task->markAsStarted();
        $firstTouch = $task->fresh()->first_touched_at;
        $this->assertNotNull($firstTouch);

        // Wait a second (mock time if needed, but simple sleep works for logic check or Carbon::setTestNow)
        Carbon::setTestNow(now()->addHour());

        $task->markAsStarted();
        $this->assertEquals($firstTouch->toDateTimeString(), $task->fresh()->first_touched_at->toDateTimeString());

        Carbon::setTestNow(); // Reset
    }

    /** @test */
    public function start_action_sets_lead_days_and_started_before_h7()
    {
        Carbon::setTestNow('2026-01-01 10:00:00');

        // Due date 10 days from now
        $task = Task::factory()->create([
            'due_date' => now()->addDays(10),
            'first_touched_at' => null
        ]);

        $task->markAsStarted();

        $this->assertEquals(10, $task->fresh()->started_lead_days);
        $this->assertTrue($task->fresh()->started_before_h7);

        // Due date 3 days from now
        $task2 = Task::factory()->create([
            'due_date' => now()->addDays(3),
            'first_touched_at' => null
        ]);

        $task2->markAsStarted();
        $this->assertEquals(3, $task2->fresh()->started_lead_days);
        $this->assertFalse($task2->fresh()->started_before_h7);

        Carbon::setTestNow();
    }

    /** @test */
    public function weekly_count_calculation_correct()
    {
        Carbon::setTestNow('2026-01-14 12:00:00'); // Wednesday

        StudySession::create([
            'user_id' => $this->user->id,
            'started_at' => now()->subDay(), // Tuesday
            'duration_min' => 25
        ]);

        StudySession::create([
            'user_id' => $this->user->id,
            'started_at' => now()->subDays(10), // Last week
            'duration_min' => 25
        ]);

        $service = new StudyStatsService();
        $this->assertEquals(1, $service->weeklyCount());

        Carbon::setTestNow();
    }

    /** @test */
    public function daily_streak_calculation()
    {
        Carbon::setTestNow('2026-01-15 12:00:00'); // Today

        // Session Today
        StudySession::create(['user_id' => $this->user->id, 'started_at' => '2026-01-15 10:00:00', 'duration_min' => 25]);
        // Session Yesterday
        StudySession::create(['user_id' => $this->user->id, 'started_at' => '2026-01-14 10:00:00', 'duration_min' => 25]);
        // Session Day Before
        StudySession::create(['user_id' => $this->user->id, 'started_at' => '2026-01-13 10:00:00', 'duration_min' => 25]);

        // Gap on 12th

        // Session on 11th
        StudySession::create(['user_id' => $this->user->id, 'started_at' => '2026-01-11 10:00:00', 'duration_min' => 25]);

        $service = new StudyStatsService();
        $this->assertEquals(3, $service->currentDailyStreak());

        Carbon::setTestNow();
    }

    /** @test */
    public function quick_log_creates_session_with_expected_duration()
    {
        Livewire::test(StudyQuickLogWidget::class)
            ->call('logSession', 50);

        $this->assertDatabaseHas('study_sessions', [
            'user_id' => $this->user->id,
            'duration_min' => 50,
            'mode' => 'study'
        ]);

        $session = StudySession::first();
        $this->assertNotNull($session->ended_at);
        $this->assertEquals(50, $session->ended_at->diffInMinutes($session->started_at));
    }

    /** @test */
    public function linking_session_to_task_sets_first_touched_if_null()
    {
        $task = Task::factory()->create(['first_touched_at' => null]);

        Livewire::test(StudyQuickLogWidget::class)
            ->set('task_id', $task->id)
            ->call('logSession', 25);

        $this->assertNotNull($task->fresh()->first_touched_at);
    }

    /** @test */
    public function weekly_review_metrics_h7()
    {
        Carbon::setTestNow('2026-01-15');

        // Task 1: Started Early (Good)
        $t1 = Task::factory()->create(['due_date' => now()->addDays(20)]);
        $t1->markAsStarted();

        // Task 2: Started Late (Bad)
        $t2 = Task::factory()->create(['due_date' => now()->addDays(3)]);
        $t2->markAsStarted();

        // Task 3: Not Started Yet (Ignored for H-7 metric calculation? No, strictly "Tasks started before H-7" vs "Total Evaluated")
        // Logic in Page: "Count tasks where started_before_h7 is true / Total tasks with due dates".
        // If untouched, started_before_h7 is false.
        $t3 = Task::factory()->create(['due_date' => now()->addDays(5), 'first_touched_at' => null]);

        // Total Evaluated = 3 (all recent/active with due dates)
        // Started Early = 1 (t1) -> started lead days ~20.
        // t2 -> started lead days 3 (False).
        // t3 -> started lead days null (False).

        // Percentage = 1/3 = 33%

        Livewire::test(WeeklyReviewPage::class)
            ->assertSet('startEarly.percentage', 33);

        Carbon::setTestNow();
    }

    /** @test */
    public function weekly_plan_save_and_load()
    {
        $task1 = Task::factory()->create();
        $task2 = Task::factory()->create();

        Livewire::test(WeeklyReviewPage::class)
            ->set('data.focus_task_ids', [$task1->id, $task2->id])
            ->set('data.note', 'Commitment Test')
            ->call('submitPlan')
            ->assertNotified();

        $this->assertDatabaseHas('weekly_plans', [
            'user_id' => $this->user->id,
            'note' => 'Commitment Test'
        ]);

        $plan = WeeklyPlan::first();
        $this->assertCount(2, $plan->focus_task_ids);
    }
}
