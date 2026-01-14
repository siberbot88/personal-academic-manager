<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\InboxItem;
use App\Models\Material;
use App\Services\InboxPromoter;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Skipping material seeding.');
            return;
        }

        $courseA = $courses->first();
        $courseB = $courses->skip(1)->first() ?? $courseA;

        // Promote 2 inbox items
        $inboxItems = InboxItem::where('status', 'inbox')->limit(2)->get();
        $promoter = new InboxPromoter();

        foreach ($inboxItems as $item) {
            $promoter->promoteToMaterial($item, ['type' => 'link']);
        }

        // Create manual note material
        $noteMateri = Material::create([
            'course_id' => $courseA->id,
            'title' => 'Catatan Kuliah: Design Patterns',
            'type' => 'note',
            'note' => "# Observer Pattern\n\nPattern untuk memberitahu objek lain tentang perubahan state.\n\n## Kegunaan:\n- Event handling\n- MVC architecture\n- Pub/Sub systems",
            'source' => 'Manual',
            'captured_at' => now()->subDays(3),
        ]);
        $noteMateri->syncTags(['design-pattern', 'catatan', 'penting']);

        // Create manual link material
        $linkMateri = Material::create([
            'course_id' => $courseB->id,
            'title' => 'Tutorial Laravel Best Practices 2024',
            'type' => 'link',
            'url' => 'https://laravel.com/docs/11.x/best-practices',
            'note' => 'Referensi coding standards dan best practices Laravel',
            'source' => 'LMS',
            'captured_at' => now()->subDays(1),
        ]);
        $linkMateri->syncTags(['laravel', 'tutorial', 'best-practice']);

        // Create file material (without actual file)
        $fileMateri = Material::create([
            'course_id' => $courseB->id,
            'title' => 'Slide Materi UML Diagrams',
            'type' => 'file',
            'note' => 'Slide lengkap tentang class diagram dan sequence diagram',
            'source' => 'Drive',
            'captured_at' => now(),
        ]);
        $fileMateri->syncTags(['slide', 'uml', 'diagram']);

        $this->command->info('Created ' . Material::count() . ' materials (2 promoted + 3 manual)');
    }
}
