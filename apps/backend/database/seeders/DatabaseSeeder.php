<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only seed reference/template data
        // NO user data, NO runtime data (semesters, courses, tasks, inbox, materials)
        $this->call([
            PamReferenceSeeder::class,
        ]);
    }
}
