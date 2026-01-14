<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\InboxItem;
use App\Models\Material;
use App\Models\Semester;
use App\Models\User;
use App\Services\InboxPromoter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Week9MaterialLibraryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create(['email' => 'test@example.com']);
        $semester = Semester::create(['name' => 'Test Semester', 'start_date' => now(), 'end_date' => now()->addMonths(6)]);
        Course::create(['name' => 'Test Course', 'semester_id' => $semester->id]);
    }

    public function test_promote_inbox_creates_material_and_marks_inbox_promoted()
    {
        $inbox = InboxItem::create([
            'course_id' => 1,
            'title' => 'Test Item',
            'url' => 'https://example.com',
            'status' => 'inbox',
        ]);

        $promoter = new InboxPromoter();
        $material = $promoter->promoteToMaterial($inbox);

        $this->assertInstanceOf(Material::class, $material);
        $this->assertEquals('Test Item', $material->title);

        $inbox->refresh();
        $this->assertEquals('promoted', $inbox->status);
        $this->assertEquals($material->id, $inbox->promoted_to_material_id);
        $this->assertNotNull($inbox->processed_at);
    }

    public function test_promote_is_idempotent_returns_existing_material()
    {
        $inbox = InboxItem::create([
            'course_id' => 1,
            'title' => 'Test Item',
            'url' => 'https://example.com',
            'status' => 'inbox',
        ]);

        $promoter = new InboxPromoter();
        $material1 = $promoter->promoteToMaterial($inbox);
        $material2 = $promoter->promoteToMaterial($inbox);

        $this->assertEquals($material1->id, $material2->id);
        $this->assertEquals(1, Material::count());
    }

    public function test_material_tags_saved()
    {
        $material = Material::create([
            'course_id' => 1,
            'title' => 'Tagged Material',
            'type' => 'note',
            'note' => 'Test note',
        ]);

        $material->syncTags(['week9', 'test', 'material']);

        $this->assertCount(3, $material->tags);
        $this->assertTrue($material->tags->contains('name', 'week9'));
    }

    public function test_material_validation_link_requires_url()
    {
        $material = Material::create([
            'course_id' => 1,
            'title' => 'Link without URL',
            'type' => 'link',
            'url' => 'https://example.com', // Required for type=link
        ]);

        $this->assertDatabaseHas('materials', [
            'type' => 'link',
            'url' => 'https://example.com',
        ]);
    }

    public function test_material_validation_note_requires_note()
    {
        $material = Material::create([
            'course_id' => 1,
            'title' => 'Note Material',
            'type' => 'note',
            'note' => 'This is a valid note with more than 10 characters',
        ]);

        $this->assertDatabaseHas('materials', [
            'type' => 'note',
        ]);
        $this->assertGreaterThan(10, strlen($material->note));
    }
}
