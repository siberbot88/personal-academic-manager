<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use App\Support\R2ClientFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateAttachmentsToR2 extends Command
{
    protected $signature = 'pam:attachments:migrate-local-to-r2 {--limit=10} {--dry-run} {--delete-local-after}';
    protected $description = 'Migrate local_private attachments to R2';

    public function handle()
    {
        $limit = $this->option('limit');
        $dryRun = $this->option('dry-run');
        $deleteLocal = $this->option('delete-local-after');

        $attachments = Attachment::where('storage_driver', 'local_private')
            ->limit($limit)
            ->get();

        $this->info("Found {$attachments->count()} attachments to migrate.");

        $s3 = R2ClientFactory::make();
        $bucket = config('pam.r2.bucket');

        foreach ($attachments as $attachment) {
            $this->info("Processing: {$attachment->original_name} (ID: {$attachment->id})");

            $localDisk = Storage::disk('private');
            if (!$localDisk->exists($attachment->storage_path)) {
                $this->error("File not found locally: {$attachment->storage_path}");
                continue;
            }

            // Generate Object Key
            // Convention: attachments/{YYYY}/{MM}/{group_id}/{uuid}_{name}
            // We need to ensure group exists or create one if missing
            if (!$attachment->attachment_group_id) {
                // Should run migration logic to create groups first?
                // Or just create on the fly.
                // Assuming we ran migration that sets group_id? No, migration made it nullable.
                // Let's create a group for legacy attachments.
                if (!$dryRun) {
                    $group = \App\Models\AttachmentGroup::create(['name' => $attachment->original_name]);
                    $attachment->update(['attachment_group_id' => $group->id]);
                } else {
                    $this->comment("Would create group for ID {$attachment->id}");
                }
            }

            $groupId = $attachment->attachment_group_id ?? 'legacy';
            $uuid = Str::uuid();
            $ext = pathinfo($attachment->original_name, PATHINFO_EXTENSION);
            $safeName = Str::slug(pathinfo($attachment->original_name, PATHINFO_FILENAME)) . '.' . $ext;
            $datePath = $attachment->created_at->format('Y/m');
            $objectKey = "attachments/{$datePath}/{$groupId}/{$uuid}_{$safeName}";

            if ($dryRun) {
                $this->comment("DRY RUN: Would upload to {$objectKey}");
                continue;
            }

            // Upload
            try {
                $fileStream = $localDisk->readStream($attachment->storage_path);
                $s3->putObject([
                    'Bucket' => $bucket,
                    'Key' => $objectKey,
                    'Body' => $fileStream,
                    'ContentType' => $attachment->mime_type,
                ]);

                // Update DB
                $attachment->update([
                    'storage_driver' => 'r2',
                    'storage_path' => $objectKey,
                ]);

                $this->info("Migrated to R2: {$objectKey}");

                if ($deleteLocal) {
                    $localDisk->delete($attachment->storage_path);
                    $this->comment("Deleted local file.");
                }

            } catch (\Exception $e) {
                $this->error("Failed to upload: " . $e->getMessage());
            }
        }

        $this->info("Migration completed.");
    }
}
