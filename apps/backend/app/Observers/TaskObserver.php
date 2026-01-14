<?php

namespace App\Observers;

use App\Models\Task;
use App\Services\HealthScorer;
use App\Services\TaskPhaseGenerator;

class TaskObserver
{
    public function created(Task $task): void
    {
        if ($task->task_type_template_id) {
            app(TaskPhaseGenerator::class)->generate($task);
        }
    }

    public function updated(Task $task): void
    {
        // Trigger health recalc for relevant changes
        if ($task->wasChanged(['status', 'due_date', 'progress_pct'])) {
            app(HealthScorer::class)->recalcTask($task);
        }

        // Event-based alerts (Week 8)
        // Only for Active tasks
        if ($task->status === 'Active') {
            // Stagnation Alert: attention_flag changed from false -> true
            if ($task->wasChanged('attention_flag') && $task->attention_flag === true) {
                \App\Jobs\SendStagnationAlertJob::dispatch($task->id);
            }

            // Bahaya Alert: health_status changed TO 'bahaya'
            if ($task->wasChanged('health_status') && $task->health_status === 'bahaya') {
                \App\Jobs\SendBahayaAlertJob::dispatch($task->id);
            }
        }
    }
}
