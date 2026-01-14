<?php

namespace App\Jobs;

use App\Mail\BahayaAlertMail;
use App\Models\Task;
use App\Services\NotificationThrottle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBahayaAlertJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $taskId)
    {
    }

    public function handle(): void
    {
        $task = Task::with('primaryCourse')->find($this->taskId);
        if (!$task || $task->status !== 'Active') {
            return;
        }

        $throttle = app(NotificationThrottle::class);
        $email = config('pam.notify_email');

        // Throttle: Max 1 per 24h per task
        if ($throttle->alreadySentWithinHours('bahaya_alert', $task->id, config('pam.throttle.event_hours'))) {
            return;
        }

        Mail::to($email)->send(new BahayaAlertMail($task));
        $throttle->logSent('bahaya_alert', $email, $task->id, [
            'title' => $task->title,
            'health_score' => $task->health_score,
        ]);
    }
}
