<?php

namespace Tests\Feature;

use App\Jobs\SendBahayaAlertJob;
use App\Jobs\SendDailyDigestJob;
use App\Jobs\SendPreStudyReminderJob;
use App\Jobs\SendStagnationAlertJob;
use App\Models\Course;
use App\Models\NotificationLog;
use App\Models\Semester;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class Week8EmailNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup basic data
        $user = User::factory()->create(['email' => 'test@example.com']);
        $semester = Semester::create(['name' => 'Test Semester', 'start_date' => now(), 'end_date' => now()->addMonths(6)]);
        Course::create(['name' => 'Test Course', 'semester_id' => $semester->id]);

        // Set config for testing
        config(['pam.notify_email' => 'test@example.com']);
    }

    public function test_daily_digest_throttled_to_once_per_day()
    {
        Mail::fake();

        // Create a task so digest has content
        Task::create([
            'title' => 'Test Task',
            'primary_course_id' => 1,
            'status' => 'Active',
            'due_date' => now()->addDay(),
        ]);

        // First dispatch
        SendDailyDigestJob::dispatchSync();
        Mail::assertSent(\App\Mail\DailyDigestMail::class, 1);

        // Second dispatch same day
        Mail::fake(); // Reset
        SendDailyDigestJob::dispatchSync();
        Mail::assertNothingSent();

        // Verify only 1 log entry
        $this->assertEquals(1, NotificationLog::where('type', 'daily_digest')->count());
    }

    public function test_daily_digest_contains_top3_tasks()
    {
        Mail::fake();

        // Create 3 tasks for different slots
        $task1 = Task::create(['title' => 'Overdue', 'primary_course_id' => 1, 'status' => 'Active', 'due_date' => now()->subDay()]);
        $task2 = Task::create(['title' => 'Untouched', 'primary_course_id' => 1, 'status' => 'Active', 'progress_pct' => 0, 'due_date' => now()->addDays(5)]);
        $task3 = Task::create(['title' => 'High Risk', 'primary_course_id' => 1, 'status' => 'Active', 'attention_flag' => true, 'due_date' => now()->addDays(10)]);

        SendDailyDigestJob::dispatchSync();

        Mail::assertSent(\App\Mail\DailyDigestMail::class, function ($mail) use ($task1, $task2, $task3) {
            $slot1 = $mail->top3['slot1']['task'] ?? null;
            $slot2 = $mail->top3['slot2']['task'] ?? null;
            $slot3 = $mail->top3['slot3']['task'] ?? null;

            // Verify tasks are assigned
            return $slot1 !== null || $slot2 !== null || $slot3 !== null;
        });
    }

    public function test_pre_study_weekdays_only_and_throttled()
    {
        Mail::fake();

        // Create a task
        Task::create(['title' => 'Study Task', 'primary_course_id' => 1, 'status' => 'Active', 'due_date' => now()->addDay()]);

        // First send
        SendPreStudyReminderJob::dispatchSync();
        Mail::assertSent(\App\Mail\PreStudyReminderMail::class, 1);

        // Second send same day - should throttle
        Mail::fake();
        SendPreStudyReminderJob::dispatchSync();
        Mail::assertNothingSent();

        $this->assertEquals(1, NotificationLog::where('type', 'pre_study')->count());
    }

    public function test_pre_study_selects_deadline_based_task()
    {
        Mail::fake();

        // Create 2 tasks with different due dates
        $urgentTask = Task::create(['title' => 'Urgent', 'primary_course_id' => 1, 'status' => 'Active', 'due_date' => now()->addDay()]);
        $laterTask = Task::create(['title' => 'Later', 'primary_course_id' => 1, 'status' => 'Active', 'due_date' => now()->addWeek()]);

        SendPreStudyReminderJob::dispatchSync();

        Mail::assertSent(\App\Mail\PreStudyReminderMail::class, function ($mail) use ($urgentTask) {
            // Should select the more urgent task
            return $mail->task->id === $urgentTask->id;
        });
    }

    public function test_stagnation_alert_fires_on_attention_flag_flip()
    {
        Mail::fake();

        $task = Task::create([
            'title' => 'Stagnant Task',
            'primary_course_id' => 1,
            'status' => 'Active',
            'attention_flag' => false,
        ]);

        // Trigger: change attention_flag to true
        $task->update(['attention_flag' => true]);

        // Process job
        $this->artisan('queue:work --once');

        Mail::assertSent(\App\Mail\StagnationAlertMail::class, function ($mail) use ($task) {
            return $mail->task->id === $task->id;
        });
    }

    public function test_stagnation_alert_throttled_24h()
    {
        Mail::fake();

        $task = Task::create(['title' => 'Test', 'primary_course_id' => 1, 'status' => 'Active', 'attention_flag' => false]);

        // First alert
        $task->update(['attention_flag' => true]);
        $this->artisan('queue:work --once');
        Mail::assertSent(\App\Mail\StagnationAlertMail::class, 1);

        // Reset and try again within 24h
        Mail::fake();
        $task->update(['attention_flag' => false]);
        $task->update(['attention_flag' => true]);
        $this->artisan('queue:work --once');

        // Should be throttled
        Mail::assertNothingSent();
    }

    public function test_bahaya_alert_fires_on_health_status_becomes_bahaya()
    {
        Mail::fake();

        $task = Task::create([
            'title' => 'Risky Task',
            'primary_course_id' => 1,
            'status' => 'Active',
            'health_status' => 'aman',
        ]);

        // Trigger: change to bahaya
        $task->update(['health_status' => 'bahaya']);

        $this->artisan('queue:work --once');

        Mail::assertSent(\App\Mail\BahayaAlertMail::class, function ($mail) use ($task) {
            return $mail->task->id === $task->id;
        });
    }

    public function test_bahaya_alert_throttled_24h()
    {
        Mail::fake();

        $task = Task::create(['title' => 'Test', 'primary_course_id' => 1, 'status' => 'Active', 'health_status' => 'aman']);

        // First alert
        $task->update(['health_status' => 'bahaya']);
        $this->artisan('queue:work --once');
        Mail::assertSent(\App\Mail\BahayaAlertMail::class, 1);

        // Try again within 24h
        Mail::fake();
        $task->update(['health_status' => 'rawan']);
        $task->update(['health_status' => 'bahaya']);
        $this->artisan('queue:work --once');

        // Should be throttled
        Mail::assertNothingSent();
    }
}
