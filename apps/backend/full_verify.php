<?php

use App\Models\Task;
use App\Models\TaskTypeTemplate;
use Illuminate\Support\Facades\DB;

try {
    echo "--- FULL VERIFICATION (WEEK 4 & 5) ---\n";

    // --- WEEK 4: GENERATION ---
    echo "\n[WEEK 4] Auto-splitting Engine\n";
    $template = TaskTypeTemplate::where('name', 'Laporan Akhir')->first();
    if (!$template) {
        throw new Exception("Template 'Laporan Akhir' not found.");
    }

    $task = Task::create([
        'title' => 'Full Test ' . time(),
        'primary_course_id' => 1,
        'status' => 'Active',
        'due_date' => now()->addDays(30),
        'task_type_template_id' => $template->id,
        'size' => 'big',
    ]);

    echo "1. Task Created: {$task->title}\n";

    $phases = \App\Models\TaskPhase::where('task_id', $task->id)->get();
    echo "2. Phases Generated: " . $phases->count() . "\n";
    if ($phases->count() == 0)
        throw new Exception("No phases.");

    // --- WEEK 5: PROGRESS TRACKING ---
    echo "\n[WEEK 5] Progress Tracking\n";

    $phase = $phases->first();
    $items = $phase->checklistItems;
    $item = $items->first();

    echo "3. Initial Phase Progress: {$phase->progress_pct}%\n";
    echo "4. Initial Task Progress: {$task->progress_pct}%\n";

    echo "5. Toggling Item: '{$item->title}' -> DONE\n";
    $item->is_done = true;
    $item->done_at = now();
    $item->save(); // Triggers Observer

    $phase->refresh();
    $task->refresh();

    echo "6. Phase Progress After: {$phase->progress_pct}%\n";
    echo "7. Task Progress After: {$task->progress_pct}%\n";

    $expectedPhase = floor((1 / $items->count()) * 100);
    $expectedTask = floor($expectedPhase / $phases->count()); // Roughly

    echo "   (Expected Phase ~{$expectedPhase}%)\n";

    if ($phase->progress_pct > 0) {
        echo "SUCCESS: Progress updated!\n";
    } else {
        echo "FAILURE: Progress did not update.\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
