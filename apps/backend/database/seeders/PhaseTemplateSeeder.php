<?php

namespace Database\Seeders;

use App\Models\PhaseTemplate;
use Illuminate\Database\Seeder;

class PhaseTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $phases = [
            ['name' => 'Riset & Referensi', 'sort_order' => 1, 'is_default' => true],
            ['name' => 'Penulisan Draft', 'sort_order' => 2, 'is_default' => true],
            ['name' => 'Revisi & Perbaikan', 'sort_order' => 3, 'is_default' => true],
            ['name' => 'Finalisasi', 'sort_order' => 4, 'is_default' => true],
            ['name' => 'Submit & Serahkan', 'sort_order' => 5, 'is_default' => true],
        ];

        foreach ($phases as $phase) {
            PhaseTemplate::updateOrCreate(
                ['name' => $phase['name']],
                [
                    'sort_order' => $phase['sort_order'],
                    'is_default' => $phase['is_default'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✓ PhaseTemplate seeded (5 phases)');
    }
}
