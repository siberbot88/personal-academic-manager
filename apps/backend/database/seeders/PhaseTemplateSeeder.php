<?php

namespace Database\Seeders;

use App\Models\PhaseTemplate;
use Illuminate\Database\Seeder;

class PhaseTemplateSeeder extends Seeder
{
    public function run()
    {
        $phases = [
            ['name' => 'Riset', 'sort_order' => 1, 'is_default' => true],
            ['name' => 'Draft', 'sort_order' => 2, 'is_default' => true],
            ['name' => 'Struktur', 'sort_order' => 3, 'is_default' => true],
            ['name' => 'Revisi & Penyempurnaan', 'sort_order' => 4, 'is_default' => true],
            ['name' => 'Finalisasi', 'sort_order' => 5, 'is_default' => true],
        ];

        foreach ($phases as $phase) {
            PhaseTemplate::firstOrCreate(
                ['name' => $phase['name']],
                $phase
            );
        }
    }
}
