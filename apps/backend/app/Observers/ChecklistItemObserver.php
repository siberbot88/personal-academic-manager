<?php

namespace App\Observers;

use App\Models\ChecklistItem;
use App\Services\HealthScorer;
use App\Services\ProgressCalculator;

class ChecklistItemObserver
{
    protected ProgressCalculator $calculator;
    protected HealthScorer $scorer;

    public function __construct(ProgressCalculator $calculator, HealthScorer $scorer)
    {
        $this->calculator = $calculator;
        $this->scorer = $scorer;
    }

    /**
     * Handle the ChecklistItem "saved" event.
     */
    public function saved(ChecklistItem $checklistItem): void
    {
        if ($checklistItem->taskPhase) {
            $this->calculator->recalcForPhase($checklistItem->taskPhase);

            // Update last_progress_at and recalc health
            $task = $checklistItem->taskPhase->task;
            if ($task) {
                $task->updateQuietly(['last_progress_at' => now()]);
                $this->scorer->recalcTask($task);
            }
        }
    }

    /**
     * Handle the ChecklistItem "deleted" event.
     */
    public function deleted(ChecklistItem $checklistItem): void
    {
        if ($checklistItem->taskPhase) {
            $this->calculator->recalcForPhase($checklistItem->taskPhase);

            // Update last_progress_at and recalc health
            $task = $checklistItem->taskPhase->task;
            if ($task) {
                $task->updateQuietly(['last_progress_at' => now()]);
                $this->scorer->recalcTask($task);
            }
        }
    }
}
