<?php

namespace App\Jobs;

use App\Mail\DailyDigestMail;
use App\Services\NotificationThrottle;
use App\Services\Top3Selector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendDailyDigestJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $throttle = app(NotificationThrottle::class);
        $email = config('pam.notify_email');

        // Throttle: Only send once per day
        if ($throttle->alreadySentToday('daily_digest', $email)) {
            return;
        }

        // Get Top 3 tasks
        $selector = app(Top3Selector::class);
        $top3Raw = $selector->getTop3();

        // Format data for email view
        $top3 = [
            'slot1' => [
                'task' => $top3Raw['slot1'],
                'reason' => $selector->getReason($top3Raw['slot1'], 'slot1'),
            ],
            'slot2' => [
                'task' => $top3Raw['slot2'],
                'reason' => $selector->getReason($top3Raw['slot2'], 'slot2'),
            ],
            'slot3' => [
                'task' => $top3Raw['slot3'],
                'reason' => $selector->getReason($top3Raw['slot3'], 'slot3'),
            ],
        ];

        // Send email
        Mail::to($email)->send(new DailyDigestMail($top3));

        // Log sent
        $taskIds = array_filter([
            $top3Raw['slot1']?->id,
            $top3Raw['slot2']?->id,
            $top3Raw['slot3']?->id,
        ]);

        $throttle->logSent('daily_digest', $email, null, [
            'task_ids' => $taskIds,
        ]);
    }
}
