<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskPhase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class Top3Selector
{
    /**
     * Get Top 3 Tasks for the Dashboard.
     * 
     * @return array{slot1: ?Task, slot2: ?Task, slot3: ?Task}
     */
    public function getTop3(): array
    {
        // 1. Fetch Candidates (Active Only)
        // Optimization: Eager load course, subquery nearest phase due
        // We fetch enough candidates to fill 3 slots (e.g. 50 is safe)
        // We don't filter strictly by slot criteria in SQL to allow flexibility, 
        // but we can sort by general priority to ensure top candidates are in the retrieved set.

        $candidates = Task::query()
            ->where('status', 'Active')
            ->with(['primaryCourse', 'taskPhases.checklistItems'])
            ->addSelect([
                'nearest_phase_due' => TaskPhase::selectRaw('MIN(due_date)')
                    ->whereColumn('task_id', 'tasks.id')
                    ->where('progress_pct', '<', 100) // Only open phases
            ])
            // Optimization: Get raw due_date for fallback
            ->orderBy('priority_boost', 'desc')
            ->orderBy('health_score', 'asc')
            ->orderBy('due_date', 'asc')
            ->limit(50)
            ->get();

        // Post-process candidates to have a unified 'effective_due_date'
        // effective_due_date = nearest_phase_due ?? task.due_date
        $candidates->each(function ($task) {
            $task->effective_due = $task->nearest_phase_due
                ? Carbon::parse($task->nearest_phase_due)
                : $task->due_date;
        });

        $excludedIds = [];

        $slot1 = $this->pickSlot1($candidates, $excludedIds);
        if ($slot1)
            $excludedIds[] = $slot1->id;

        $slot2 = $this->pickSlot2($candidates, $excludedIds);
        if ($slot2)
            $excludedIds[] = $slot2->id;

        $slot3 = $this->pickSlot3($candidates, $excludedIds);

        return [
            'slot1' => $slot1,
            'slot2' => $slot2,
            'slot3' => $slot3,
        ];
    }

    /**
     * Slot 1: Deadline Terdekat / Overdue
     * Rules: 
     * - Active
     * - Sort by effective_due ASC
     * - Prefer Overdue (effective_due < now)
     */
    protected function pickSlot1(Collection $candidates, array $excludedIds): ?Task
    {
        // Filter out excluded
        $pool = $candidates->whereNotIn('id', $excludedIds);

        // Sort by effective_due ASC. Nulls last.
        // If effective_due is null? Put it last.

        $sorted = $pool->sortBy(function ($task) {
            return $task->effective_due ? $task->effective_due->timestamp : 9999999999;
        });

        return $sorted->first();
    }

    /**
     * Slot 2: Belum Disentuh
     * Rules:
     * - Active
     * - progress_pct == 0
     * - Sort via effective_due ASC
     */
    protected function pickSlot2(Collection $candidates, array $excludedIds): ?Task
    {
        $pool = $candidates->whereNotIn('id', $excludedIds)
            ->where('progress_pct', 0);

        $sorted = $pool->sortBy(function ($task) {
            return $task->effective_due ? $task->effective_due->timestamp : 9999999999;
        });

        return $sorted->first();
    }

    /**
     * Slot 3: Fokus Utama (Risiko Tertinggi)
     * Rules:
     * - Active
     * - Sort: 
     *   1) attention_flag DESC (true first)
     *   2) health_status (Bahaya > Rawan > Aman) -> Implicitly Health Score ASC
     *   3) health_score ASC
     *   4) effective_due ASC
     */
    protected function pickSlot3(Collection $candidates, array $excludedIds): ?Task
    {
        $pool = $candidates->whereNotIn('id', $excludedIds);

        // Sort logic
        $sorted = $pool->sort(function ($a, $b) {
            // 1. Attention Flag (True < False in ASC? No, True > False. We want DESC behavior)
            // $a=true, $b=false -> -1 (a comes first)
            if ($a->attention_flag !== $b->attention_flag) {
                return $a->attention_flag ? -1 : 1;
            }

            // 2. Health Score ASC (Lower is riskier)
            if ($a->health_score !== $b->health_score) {
                return $a->health_score <=> $b->health_score;
            }

            // 3. Effective Due ASC (Sooner is riskier)
            $tsA = $a->effective_due ? $a->effective_due->timestamp : 9999999999;
            $tsB = $b->effective_due ? $b->effective_due->timestamp : 9999999999;

            return $tsA <=> $tsB;
        });

        return $sorted->first();
    }

    public function getReason(?Task $task, string $slot): string
    {
        if (!$task)
            return '';

        switch ($slot) {
            case 'slot1':
                if ($task->effective_due && $task->effective_due->isPast()) {
                    return 'Overdue';
                }
                return 'Deadline Terdekat';
            case 'slot2':
                return 'Belum Disentuh';
            case 'slot3':
                if ($task->stagnation_days >= 3)
                    return 'Stagnan 3+ Hari';
                if ($task->health_status === 'bahaya')
                    return 'Health: Bahaya';
                return 'Risiko Tinggi';
            default:
                return 'Top Focus';
        }
    }
}
