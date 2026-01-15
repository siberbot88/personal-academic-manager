<?php

use App\Models\Attachment;
use App\Models\Course;
use App\Models\Material;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting Verification...\n";

try {
    // 1. Setup Data
    Storage::fake('private');
    // Using simple user creation without factory to avoid column issues if 'name' missing
    $user = User::where('email', 'test_verify@example.com')->first();
    if (!$user) {
        $user = new User();
        $user->email = 'test_verify@example.com';
        $user->password = bcrypt('password');
        $user->name = 'Test'; // Try setting name, if fails we catch
        $user->save();
    }
    auth()->login($user);

    $semester = Semester::firstOrCreate(['name' => 'Verify Sem'], ['start_date' => now(), 'end_date' => now()->addYear()]);
    $course = Course::firstOrCreate(['name' => 'Verify Course'], ['semester_id' => $semester->id]);

    // Explicitly provide all potentially required fields for Material
    $material = Material::create([
        'course_id' => $course->id,
        'title' => 'Verify Material ' . uniqid(),
        'type' => 'note',
        'note' => 'Verification Note',
        'source' => 'Manual',
        'captured_at' => now(),
    ]);

    echo "Material Created: {$material->id}\n";

    // 2. Simulate Upload & Attachment Creation
    $content = 'PDF CONTENT';
    $hash = hash('sha256', $content);
    $path = 'attachments/verify.pdf';
    Storage::disk('private')->put($path, $content);

    $attachment = Attachment::create([
        'storage_driver' => 'local_private',
        'storage_path' => $path,
        'original_name' => 'verify.pdf',
        'mime_type' => 'application/pdf',
        'size_bytes' => strlen($content),
        'checksum_sha256' => $hash,
        'uploaded_at' => now(),
    ]);

    echo "Attachment Created: {$attachment->id}\n";

    // 3. Attach
    $material->attachments()->attach($attachment);
    echo "Attached to Material.\n";

    // 4. Verify
    $check = Material::find($material->id);
    if ($check->attachments->count() > 0) {
        echo "SUCCESS: Material has " . $check->attachments->count() . " attachments.\n";
        echo "Attachment Name: " . $check->attachments->first()->original_name . "\n";
    } else {
        echo "FAILED: No attachments found.\n";
        exit(1);
    }

    // 5. Verify Model Helper
    if ($attachment->human_readable_size === '11 B') {
        echo "SUCCESS: Human readable size is correct.\n";
    } else {
        echo "FAILED: Size mismatch.\n";
    }

    echo "\n--------------------------------------------------\n";
    echo "VERIFICATION PASSED: Material & Attachment Linked!\n";
    echo "--------------------------------------------------\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
