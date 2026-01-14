<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Week9DebugTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_material()
    {
        $course = Course::create(['name' => 'Demo Course']);

        $material = Material::create([
            'course_id' => $course->id,
            'title' => 'Debug Material',
            'type' => 'note',
            'note' => 'Just a test',
            'source' => 'Manual',
            'captured_at' => now(),
        ]);

        $this->assertDatabaseHas('materials', ['title' => 'Debug Material']);
        $this->assertEquals('note', $material->type);
    }
}
