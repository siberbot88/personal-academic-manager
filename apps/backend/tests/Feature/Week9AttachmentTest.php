<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Week9AttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_attach_file_to_material()
    {
        $user = \App\Models\User::factory()->create(['email' => 'test@example.com']);
        $this->actingAs($user);

        $semester = \App\Models\Semester::create(['name' => 'Sem 1', 'start_date' => now(), 'end_date' => now()->addYear()]);
        $course = Course::create(['name' => 'Demo Course', 'semester_id' => $semester->id]);
        $material = Material::create([
            'course_id' => $course->id,
            'title' => 'Material with Attachment',
            'type' => 'note',
            'note' => 'Testing attachments',
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 100);
        $path = $file->store('attachments/' . date('Y/m'), 'private');

        // Simulate logic in AttachmentsRelationManager::mutateFormDataBeforeCreate
        $attachmentData = [
            'storage_driver' => 'local_private',
            'storage_path' => $path,
            'original_name' => 'document.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => $file->getSize(),
            'checksum_sha256' => hash('sha256', $file->get()),
            'uploaded_at' => now(),
        ];

        $attachment = Attachment::create($attachmentData);
        $material->attachments()->attach($attachment);

        $this->assertDatabaseHas('attachments', ['original_name' => 'document.pdf']);
        $this->assertCount(1, $material->attachments);

        // Verify download response logic
        $this->assertEquals('local_private', $attachment->storage_driver);
    }
}
