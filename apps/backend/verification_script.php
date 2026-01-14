<?php
// Verification Script for Auto-splitting Engine
use App\Models\Task;
use App\Models\TaskTypeTemplate;
use App\Models\PhaseTemplate;
use Illuminate\Support\Facades\DB;

try {
    echo "--- START VERIFICATION ---\n";

    // 1. Ensure Seed Data exists
    $template = TaskTypeTemplate::where('name', 'Laporan Akhir')->first();
    if (!$template) {
        echo "Error: Template 'Laporan Akhir' not found. Please seed DB.\n";
        exit(1);
    }
    echo "1. Template Found: " . $template->name . " (ID: $template->id)\n";

    // 2. Create Task
    echo "2. Creating Task...\n";
    $task = Task::create([
        'title' => 'Test Auto Split ' . time(),
        'primary_course_id' => \App\Models\Course::first()?->id ?? 1, // Fallback
        'status' => 'Active',
        'due_date' => now()->addDays(30), // 30 days form now
        'task_type_template_id' => $template->id,
        'size' => 'big',
    ]);

    echo "   Task Created: " . $task->title . " (ID: $task->id)\n";

    // 3. Verify Generation
    $phases = \App\Models\TaskPhase::where('task_id', $task->id)->orderBy('sort_order')->get();
    echo "3. Generated Phases: " . $phases->count() . "\n";

    if ($phases->count() > 0) {
        foreach ($phases as $p) {
            echo "   - [{$p->status}] {$p->title} (Start: {$p->start_date?->format('Y-m-d')}, Due: {$p->due_date->format('Y-m-d')})\n";
            $items = \App\Models\ChecklistItem::where('task_phase_id', $p->id)->count();
            echo "     -> Items: $items\n";
        }
        echo "SUCCESS: Auto-splitting works!\n";
    } else {
        echo "FAILURE: No phases generated.\n";
    }

} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
