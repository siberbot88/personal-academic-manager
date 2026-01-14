<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskPhase;
use App\Models\ChecklistItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaskPhaseGenerator
{
    public function generate(Task $task): void
    {
        if (!$task->taskTypeTemplate || !$task->due_date) {
            return;
        }

        $template = $task->taskTypeTemplate;
        // Load phases with pivot data (weights)
        $phases = $template->phases()->orderBy('pivot_sort_order', 'desc')->get(); // Reverse order for backward scheduling

        if ($phases->isEmpty()) {
            return;
        }

        // Calculate total available days
        // We assume start point is today for "duration check", but for scheduling we use due_date anchor.
        // Actually, for "size", we might need created_at or today.
        // But for scheduling:

        $dueDate = Carbon::parse($task->due_date);

        // We need a total duration to apply weights. 
        // If we don't have a start date, what is the duration?
        // Usually, academic tasks have a "start date" or we assume "now".
        // Let's assume the task "sized" logic gives us a duration estimate if we want?
        // OR: Phase weights summation = 100%. We apply this to the "Time Window".
        // Time Window = DueDate - CreatedAt (or Now).

        $startDate = $task->created_at ? Carbon::parse($task->created_at) : now();
        $totalDays = $startDate->diffInDays($dueDate);

        if ($totalDays <= 0) {
            $totalDays = 1; // Fallback
        }

        // Transaction to ensure atomicity
        DB::transaction(function () use ($task, $phases, $dueDate, $totalDays) {
            // Clear existing generated phases? 
            // Only if status is initial? For now, assume fresh generation or explicit re-generation.
            $task->taskPhases()->delete();

            $currentEndDate = $dueDate->copy();

            foreach ($phases as $phase) {
                $weight = $phase->pivot->weight_percent ?? 0;
                $durationDays = round(($weight / 100) * $totalDays);

                // Ensure at least 1 day if weight > 0?
                if ($durationDays < 1 && $weight > 0) {
                    $durationDays = 1;
                }

                $phaseStartDate = $currentEndDate->copy()->subDays($durationDays);

                // Create TaskPhase
                $taskPhase = TaskPhase::create([
                    'task_id' => $task->id,
                    'phase_template_id' => $phase->id,
                    'title' => $phase->name,
                    'sort_order' => $phase->pivot->sort_order,
                    'due_date' => $currentEndDate->format('Y-m-d'),
                    'start_date' => $phaseStartDate->format('Y-m-d'),
                    'status' => 'todo',
                ]);

                // Copy Checklists
                $checklistTemplates = $task->taskTypeTemplate->checklistTemplates()
                    ->where('phase_template_id', $phase->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                foreach ($checklistTemplates as $ct) {
                    ChecklistItem::create([
                        'task_phase_id' => $taskPhase->id,
                        'title' => $ct->title,
                        'sort_order' => $ct->sort_order,
                        'is_done' => false,
                    ]);
                }

                // Move anchor backward
                $currentEndDate = $phaseStartDate; // Next phase ends when this one starts
            }
        });
    }
}
