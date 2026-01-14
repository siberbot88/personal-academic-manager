<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;

class HealthScorer
{
    /**
     * Recalculate health score, status, and set flags for a task.
     */
    public function recalcTask(Task $task): void
    {
        $now = now();
        $score = 100;

        $task->refresh(); // Ensure relations like taskPhases/items are fresh if needed, but usually passed task is enough.

        // --- STAGNATION DETECTION ---
        // Rule: Task belum selesai (not Done/Archived or progress < 100) AND last_progress_at null
        // -> anggap stagnan jika task dibuat > 3 hari.

        $isDone = $task->status === 'Done' || $task->status === 'Archived' || $task->progress_pct >= 100;
        $activeDays = $task->created_at->diffInDays($now);

        $stagnationDays = 0;

        if (!$isDone) {
            if ($task->last_progress_at) {
                // stagnation_days = floor(diff(now, last_progress_at)/day)
                $stagnationDays = (int) $task->last_progress_at->diffInDays($now);
            } else {
                // If never progressed, stick to created_at
                // Jika task dibuat > 3 hari yang lalu, hitung stagnasi dari created_at
                if ($activeDays >= 3) {
                    $stagnationDays = (int) $activeDays;
                }
            }
        }

        $task->stagnation_days = $stagnationDays;

        // --- SCORE CALCULATION ---

        // 2) Overdue Phases
        // jika ada task_phase due_date < today dan phase belum selesai -> score -= 40
        $hasOverduePhase = $task->taskPhases()
            ->where('due_date', '<', $now->startOfDay())
            ->where('progress_pct', '<', 100) // Assuming phase done when 100% or we check checklist items
            ->exists();

        if ($hasOverduePhase) {
            $score -= 40;
        }

        // 3) Deadline Dekat
        // jika nearest_phase_due dalam <= 2 hari -> score -= 20
        // jika <= 7 hari -> score -= 10
        // (Note: This logic could be interpreted as "Task Due Date" or "Next Phase Due Date". 
        //  Instruction says "nearest_phase_due", implying we check upcoming phases)

        // Find next pending phase due date
        // Skip done phases.
        $nextPhase = $task->taskPhases()
            ->where('progress_pct', '<', 100)
            ->whereNotNull('due_date')
            ->where('due_date', '>=', $now->startOfDay())
            ->orderBy('due_date', 'asc')
            ->first();

        // If no phases, check task due date? Instruction says "Untuk task tanpa due_date dan tanpa phases: rule deadline di-skip."
        // Let's fallback to task due date if no phase due date found, or just stick to phases as requested.
        // Assuming strict "nearest_phase_due":

        if ($nextPhase) {
            $daysToDeadline = $now->diffInDays($nextPhase->due_date, false); // float, negative if past? we handled past above.
            // allow 0 for today

            if ($daysToDeadline <= 2) {
                $score -= 20;
            } elseif ($daysToDeadline <= 7) {
                $score -= 10;
            }
        } elseif ($task->due_date && $task->taskPhases()->count() === 0) {
            // Callback for simple tasks
            $daysToDeadline = $now->diffInDays($task->due_date, false);
            if ($daysToDeadline >= 0) {
                if ($daysToDeadline <= 2) {
                    $score -= 20;
                } elseif ($daysToDeadline <= 7) {
                    $score -= 10;
                }
            }
        }

        // 4) Stagnation Penalty
        // jika stagnation_days >= 3 dan progress_pct < 100 -> score -= 30, attention_flag = true, priority_boost = true
        if ($stagnationDays >= 3 && !$isDone) {
            $score -= 30;
            $task->attention_flag = true;
            $task->priority_boost = true;
        } else {
            // Reset automatically? "Done task resets flags" is explicitly separate rule E.
            // But if stagnation is cured (e.g. progress updated today), we should clear attention?
            // "Saat progress berubah -> Set task.last_progress_at = now() -> Recalc"
            // If last_progress_at is now, stagnationDays = 0.
            // So flag should adhere to current state.
            // However, "priority_boost" is a consequence. Maybe valid to keep it until cleared?
            // But attention_flag is strictly status.
            // Let's reset attention_flag if not stagnant.
            // But keep priority_boost? "Clear Boost... hanya untuk debugging". Implies it sticks?
            // Let's sticky priority_boost, but reset attention_flag.
            $task->attention_flag = false;
        }

        // 5) Clamp score 0..100
        if ($score < 0)
            $score = 0;
        if ($score > 100)
            $score = 100;

        $task->health_score = $score;

        // 6) Health Status
        // >= 70 aman, 40..69 rawan, <40 bahaya
        if ($score >= 70) {
            $task->health_status = 'aman';
        } elseif ($score >= 40) {
            $task->health_status = 'rawan';
        } else {
            $task->health_status = 'bahaya';
        }

        // Override: Overdue phase forces 'bahaya'
        if ($hasOverduePhase) {
            $task->health_status = 'bahaya';
        }

        // E) Consistency
        // Saat task status menjadi Done: set attention_flag=false, priority_boost=false, health_status='aman', health_score=100
        if ($isDone) {
            $task->health_score = 100;
            $task->health_status = 'aman';
            $task->attention_flag = false;
            $task->priority_boost = false;
            $task->stagnation_days = 0;
        } else {
            // Special case: If score dropped drastically (e.g. overdue), maybe set Boost?
            // "Task yang stagnan/berbahaya otomatis diberi priority_boost"
            if ($task->health_status === 'bahaya') {
                $task->priority_boost = true;
                $task->attention_flag = true; // Danger usually needs attention
            }
        }

        $task->saveQuietly();
    }
}
