<?php

namespace App\Jobs;

use App\Mail\PreStudyReminderMail;
use App\Services\NotificationThrottle;
use App\Services\StudyFocusSelector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPreStudyReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $throttle = app(NotificationThrottle::class);
        $email = config('pam.notify_email');

        // Throttle: Only once per day
        if ($throttle->alreadySentToday('pre_study', $email)) {
            return;
        }

        // Get focus task
        $selector = app(StudyFocusSelector::class);
        $task = $selector->getFocusTask();

        // Send email (even if task is null - view handles it)
        Mail::to($email)->send(new PreStudyReminderMail($task));

        // Log
        $throttle->logSent('pre_study', $email, $task?->id, [
            'task_title' => $task?->title,
        ]);
    }
}
