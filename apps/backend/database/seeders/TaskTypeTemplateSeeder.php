<?php

namespace Database\Seeders;

use App\Models\TaskTypeTemplate;
use Illuminate\Database\Seeder;

class TaskTypeTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $taskTypes = [
            [
                'name' => 'Makalah',
                'description' => 'Template untuk tugas makalah akademik dengan riset, draft, dan revisi',
                'big_threshold_days' => 11,
            ],
            [
                'name' => 'Laporan Akhir',
                'description' => 'Template untuk laporan akhir/skripsi dengan metodologi lengkap',
                'big_threshold_days' => 14,
            ],
            [
                'name' => 'Project',
                'description' => 'Template untuk project software, engineering, atau development',
                'big_threshold_days' => 20,
            ],
            [
                'name' => 'Presentasi',
                'description' => 'Template untuk persiapan presentasi atau sidang',
                'big_threshold_days' => 7,
            ],
        ];

        foreach ($taskTypes as $taskType) {
            TaskTypeTemplate::updateOrCreate(
                ['name' => $taskType['name']],
                [
                    'description' => $taskType['description'],
                    'big_threshold_days' => $taskType['big_threshold_days'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✓ TaskTypeTemplate seeded (4 types)');
    }
}
