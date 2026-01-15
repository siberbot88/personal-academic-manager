<?php

namespace Database\Seeders;

use App\Models\PhaseTemplate;
use App\Models\TaskTypeTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskTypeTemplatePhaseSeeder extends Seeder
{
    public function run(): void
    {
        // Define phase weights for each task type
        $phaseWeights = [
            'Makalah' => [
                'Riset & Referensi' => ['weight' => 25, 'sort' => 1],
                'Penulisan Draft' => ['weight' => 35, 'sort' => 2],
                'Revisi & Perbaikan' => ['weight' => 20, 'sort' => 3],
                'Finalisasi' => ['weight' => 15, 'sort' => 4],
                'Submit & Serahkan' => ['weight' => 5, 'sort' => 5],
            ],
            'Laporan Akhir' => [
                'Riset & Referensi' => ['weight' => 30, 'sort' => 1],
                'Penulisan Draft' => ['weight' => 30, 'sort' => 2],
                'Revisi & Perbaikan' => ['weight' => 20, 'sort' => 3],
                'Finalisasi' => ['weight' => 15, 'sort' => 4],
                'Submit & Serahkan' => ['weight' => 5, 'sort' => 5],
            ],
            'Project' => [
                'Riset & Referensi' => ['weight' => 15, 'sort' => 1], // Planning & Requirements
                'Penulisan Draft' => ['weight' => 45, 'sort' => 2], // Implementation (biggest)
                'Revisi & Perbaikan' => ['weight' => 20, 'sort' => 3], // Testing & Debugging
                'Finalisasi' => ['weight' => 15, 'sort' => 4], // Documentation & Polish
                'Submit & Serahkan' => ['weight' => 5, 'sort' => 5], // Deployment
            ],
            'Presentasi' => [
                'Riset & Referensi' => ['weight' => 20, 'sort' => 1], // Research content
                'Penulisan Draft' => ['weight' => 35, 'sort' => 2], // Create slides
                'Revisi & Perbaikan' => ['weight' => 25, 'sort' => 3], // Practice & refine
                'Finalisasi' => ['weight' => 15, 'sort' => 4], // Final prep
                'Submit & Serahkan' => ['weight' => 5, 'sort' => 5], // Deliver
            ],
        ];

        foreach ($phaseWeights as $taskTypeName => $phases) {
            $taskType = TaskTypeTemplate::where('name', $taskTypeName)->first();

            if (!$taskType) {
                $this->command->warn("TaskTypeTemplate '{$taskTypeName}' not found, skipping...");
                continue;
            }

            // Delete existing pivot records for idempotency
            DB::table('task_type_template_phases')
                ->where('task_type_template_id', $taskType->id)
                ->delete();

            // Insert new phase mappings
            foreach ($phases as $phaseName => $config) {
                $phase = PhaseTemplate::where('name', $phaseName)->first();

                if (!$phase) {
                    $this->command->warn("PhaseTemplate '{$phaseName}' not found, skipping...");
                    continue;
                }

                DB::table('task_type_template_phases')->insert([
                    'task_type_template_id' => $taskType->id,
                    'phase_template_id' => $phase->id,
                    'weight_percent' => $config['weight'],
                    'sort_order' => $config['sort'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info("✓ Linked {$taskTypeName} with " . count($phases) . " phases");
        }
    }
}
