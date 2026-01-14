<?php

namespace App\Services;

use App\Models\NotificationLog;
use Illuminate\Support\Carbon;

class NotificationThrottle
{
    /**
     * Check if a specific notification type has been sent to an email today.
     */
    public function alreadySentToday(string $type, string $email): bool
    {
        return NotificationLog::query()
            ->where('type', $type)
            ->where('to_email', $email)
            ->where('sent_at', '>=', Carbon::today())
            ->exists();
    }

    /**
     * Check if a specific notification type for a task has been sent within X hours.
     */
    public function alreadySentWithinHours(string $type, int $taskId, int $hours): bool
    {
        return NotificationLog::query()
            ->where('type', $type)
            ->where('task_id', $taskId)
            ->where('sent_at', '>=', now()->subHours($hours))
            ->exists();
    }

    /**
     * Log a sent notification.
     */
    public function logSent(string $type, string $email, ?int $taskId = null, array $meta = []): void
    {
        NotificationLog::create([
            'type' => $type,
            'to_email' => $email,
            'task_id' => $taskId,
            'meta' => $meta,
            'sent_at' => now(),
        ]);
    }
}
