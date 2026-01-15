<?php

namespace App\Console\Commands;

use App\Support\R2ClientFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DailyBackup extends Command
{
    protected $signature = 'pam:backup:daily';
    protected $description = 'Backup database to R2 and clean up old backups';

    public function handle()
    {
        $this->info("Starting Daily Backup...");

        $connection = config('database.default');
        $filename = 'backup_' . date('Y-m-d_H-i-s');
        $tempPath = storage_path('app/backups/' . $filename);

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        // 1. Dump Database
        try {
            if ($connection === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                $filename .= '.sqlite.gz';
                $tempPath .= '.sqlite.gz';

                $this->info("Backing up SQLite database from {$dbPath}...");

                // Simple GZIP copy
                $gz = gzopen($tempPath, 'w9');
                $handle = fopen($dbPath, 'rb');
                while (!feof($handle)) {
                    gzwrite($gz, fread($handle, 1024 * 512));
                }
                fclose($handle);
                gzclose($gz);

            } elseif ($connection === 'mysql') {
                $filename .= '.sql.gz';
                $tempPath .= '.sql.gz';
                // Implement mysqldump if needed, omitted for SQLite environment focus
                // $command = "mysqldump ... | gzip > $tempPath";
                $this->warn("MySQL backup not fully implemented in this script version.");
                return;
            }
        } catch (\Exception $e) {
            $this->error("Backup failed during dump: " . $e->getMessage());
            return;
        }

        // 2. Upload to R2
        $bucket = config('pam.r2.backup_bucket') ?: config('pam.r2.bucket');
        $prefix = config('pam.r2.backup_bucket') ? '' : 'backups/';
        $key = $prefix . $filename;
        $s3 = R2ClientFactory::make();

        try {
            $this->info("Uploading to R2 ({$bucket}) as {$key}...");
            $s3->putObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'Body' => fopen($tempPath, 'r'),
            ]);
            $this->info("Upload successful.");
        } catch (\Exception $e) {
            $this->error("Upload failed: " . $e->getMessage());
            // Clean up temp file
            @unlink($tempPath);
            return;
        }

        // Cleanup Temp
        @unlink($tempPath);

        // 3. Retention Cleanup
        $days = config('pam.backups.retention_days', 14);
        $this->info("Checking retention ({$days} days)...");

        // List objects
        $objects = $s3->listObjectsV2([
            'Bucket' => $bucket,
            'Prefix' => $prefix . 'backup_',
        ]);

        if (isset($objects['Contents'])) {
            foreach ($objects['Contents'] as $object) {
                $lastModified = $object['LastModified']; // Aws\Api\DateTimeResult
                // $lastModified is already DateTime object in AWS SDK v3
                if ($lastModified->modify("+{$days} days") < new \DateTime()) {
                    $this->info("Deleting old backup: " . $object['Key']);
                    $s3->deleteObject([
                        'Bucket' => $bucket,
                        'Key' => $object['Key'],
                    ]);
                }
            }
        }

        $this->info("Backup process finished.");
    }
}
