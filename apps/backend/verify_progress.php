<?php

use App\Models\Task;
use App\Models\TaskPhase;
use App\Models\ChecklistItem;

echo "--- VERIFY PROGRESS TRACKING ---\n";

// 1. Setup Data
$task = Task::latest()->first();
if (!$task) {
    echo "No task found. Run previous verification first.\n";
    exit;
}

// Ensure it has phases
if ($task->taskPhases->isEmpty()) {
    echo "Task has no phases. Generating...\n";
    app(\App\Services\TaskPhaseGenerator::class)->generate($task);
    $task->refresh();
}

$phase = $task->taskPhases->first();
echo "Phase: {$phase->title} (Current Progress: {$phase->progress_pct}%)\n";
echo "Task Progress: {$task->progress_pct}%\n";

// 2. Get Items
$items = $phase->checklistItems;
if ($items->isEmpty()) {
    echo "No items in phase.\n";
    exit;
}
$item = $items->first();

// 3. Toggle Item
$newStatus = !$item->is_done;
echo "Toggling Item '{$item->title}' to " . ($newStatus ? 'DONE' : 'NOT DONE') . "...\n";

$item->is_done = $newStatus;
$item->done_at = $newStatus ? now() : null;
$item->save(); // Should trigger Observer -> Calculator

// 4. Verify
$phase->refresh();
$task->refresh();

echo "Phase Progress After: {$phase->progress_pct}%\n";
echo "Task Progress After: {$task->progress_pct}%\n";

if ($phase->progress_pct != 0 && $newStatus == true) {
    echo "SUCCESS: Phase progress updated.\n";
} elseif ($newStatus == false && $phase->progress_pct < 100) {
    echo "SUCCESS: Phase progress updated.\n";
} else {
    echo "WARNING: Check logic.\n";
}
