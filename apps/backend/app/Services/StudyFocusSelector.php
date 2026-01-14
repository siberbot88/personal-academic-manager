<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Carbon;

class StudyFocusSelector
{
    /**
     * Select 1 task for pre-study focus (deadline-based).
     * 
     * Rules:
     * - Active tasks only
     * - Nearest open phase due (or task due_date if no phases)
     * - Lowest progress if tie
     */
    public function getFocusTask(): ?Task
    {
        $task = Task::query()
            ->where('status', 'Active')
            ->with('primaryCourse')
            ->addSelect([
                    'nearest_phase_due' => \App\Models\TaskPhase::selectRaw('MIN(due_date)')
                        ->whereColumn('task_id', 'tasks.id')
                        ->where('progress_pct', '<', 100)
                ])
            ->get()
            ->each(function ($task) {
                $task->effective_due = $task->nearest_phase_due
                    ? Carbon::parse($task->nearest_phase_due)
                    : $task->due_date;
            })
            ->filter(fn($task) => $task->effective_due !== null)
            ->sortBy([
                    fn($a, $b) => $a->effective_due <=> $b->effective_due,
                    fn($a, $b) => $a->progress_pct <=> $b->progress_pct,
                ])
            ->first();

        return $task;
    }
}
