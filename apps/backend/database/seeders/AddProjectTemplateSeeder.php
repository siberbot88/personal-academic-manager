<?php

namespace Database\Seeders;

use App\Models\TaskTypeTemplate;
use App\Models\PhaseTemplate;
use App\Models\ChecklistTemplate;
use Illuminate\Database\Seeder;

class AddProjectTemplateSeeder extends Seeder
{
    public function run()
    {
        // 1. Get Existing Phases
        $riset = PhaseTemplate::where('name', 'Riset')->first();
        $draft = PhaseTemplate::where('name', 'Draft')->first();
        $struktur = PhaseTemplate::where('name', 'Struktur')->first();
        $revisi = PhaseTemplate::where('name', 'Revisi & Penyempurnaan')->first();
        $final = PhaseTemplate::where('name', 'Finalisasi')->first();

        // 2. Create "Project" Template
        $project = TaskTypeTemplate::firstOrCreate(
            ['name' => 'Project'],
            [
                'description' => 'Template untuk tugas Project (Software dev, Engineering, etc).',
                'big_threshold_days' => 20, // Projects usually longer
                'is_active' => true,
            ]
        );

        // 3. Attach Phases (Plan -> Design -> Code -> Test -> Deploy)
        // Mapping generic phases to project stages:
        // Riset -> Requirement & Plan
        // Struktur -> Design & Schema
        // Draft -> Implementation (Coding)
        // Revisi -> Testing & Bugfix
        // Finalisasi -> Deployment & Doc

        $project->phases()->syncWithoutDetaching([
            $riset->id => ['weight_percent' => 15, 'sort_order' => 1],
            $struktur->id => ['weight_percent' => 15, 'sort_order' => 2],
            $draft->id => ['weight_percent' => 45, 'sort_order' => 3], // Biggest chunk
            $revisi->id => ['weight_percent' => 15, 'sort_order' => 4],
            $final->id => ['weight_percent' => 10, 'sort_order' => 5],
        ]);

        // 4. Create Checklists
        // Riset
        $this->createChecklists($project, $riset, [
            'Analisis kebutuhan user',
            'Tentukan Tech Stack',
            'Buat timeline pengerjaan',
        ]);

        // Struktur
        $this->createChecklists($project, $struktur, [
            'Desain Database (ERD)',
            'Desain API / Endpoint',
            'Desain UI/UX Mockup',
        ]);

        // Draft (Implementation)
        $this->createChecklists($project, $draft, [
            'Setup repository & environment',
            'Implementasi Backend / Core Logic',
            'Implementasi Frontend / UI',
            'Integrasi API',
        ]);

        // Revisi (Testing)
        $this->createChecklists($project, $revisi, [
            'Unit Testing',
            'Manual Testing (Feature walk)',
            'Fix bugs ditemukan',
        ]);

        // Finalisasi (Deploy)
        $this->createChecklists($project, $final, [
            'Deploy ke server/hosting',
            'Siapkan dokumentasi/README',
            'Video demo / screenshot',
        ]);
    }

    private function createChecklists($template, $phase, $items)
    {
        foreach ($items as $index => $title) {
            ChecklistTemplate::firstOrCreate([
                'task_type_template_id' => $template->id,
                'phase_template_id' => $phase->id,
                'title' => $title,
            ], [
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
