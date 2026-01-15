<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PamReferenceSeeder extends Seeder
{
    /**
     * Seed reference/template data for PAM Template Engine.
     * This seeder is idempotent and safe to run multiple times.
     *
     * NO USER DATA IS SEEDED (semesters, courses, tasks, inbox, materials, etc.)
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding PAM Reference Data...');
        $this->command->newLine();

        $this->call([
            PhaseTemplateSeeder::class,
            TaskTypeTemplateSeeder::class,
            TaskTypeTemplatePhaseSeeder::class,
            ChecklistTemplateSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ PAM Reference Data seeded successfully!');
    }
}
