<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskPhase;
use Illuminate\Support\Facades\DB;

class ProgressCalculator
{
    /**
     * Recalculate progress for a single phase based on its checklist items.
     * Then triggers task progress update.
     */
    public function recalcForPhase(TaskPhase $phase): void
    {
        DB::transaction(function () use ($phase) {
            // Refresh to ensure we have latest items
            $phase->refresh();

            $total = $phase->checklistItems()->count();
            $done = $phase->checklistItems()->where('is_done', true)->count();

            $pct = 0;
            if ($total > 0) {
                $pct = (int) floor(($done / $total) * 100);
            }

            // Only update if changed
            if ($phase->progress_pct !== $pct) {
                $phase->updateQuietly(['progress_pct' => $pct]);
                // Use updateQuietly if we don't want to trigger loops, 
                // but PhaseObserver might update status based on progress 
                // (e.g. 100% -> Done). For now, standard update is fine.
                // $phase->update(['progress_pct' => $pct]);
            }

            $this->recalcTaskProgress($phase->task);
        });
    }

    /**
     * Recalculate progress for a task based on its phases.
     */
    public function recalcTaskProgress(Task $task): void
    {
        // Don't wrap in transaction here if called from recalcForPhase 
        // (already in transaction), but safe to nest.

        $phases = $task->taskPhases; // Uses collection to avoid N+1 if loaded? 
        // Better trigger fresh query for accuracy
        $avgPct = (int) $task->taskPhases()->avg('progress_pct');

        // Handle no phases case
        if ($task->taskPhases()->count() === 0) {
            $avgPct = 0;
            // If it's a small task, maybe we mark it differently? 
            // Requirement: "Task tanpa phases => task progress 0% (Week 5)"
        }

        if ($task->progress_pct !== $avgPct) {
            $task->updateQuietly(['progress_pct' => $avgPct]);
        }
    }

    /**
     * Helper to recalc everything for a task (e.g. after generation)
     */
    public function recalcAllForTask(Task $task): void
    {
        DB::transaction(function () use ($task) {
            foreach ($task->taskPhases as $phase) {
                $this->recalcForPhase($phase);
            }
        });
    }
}
