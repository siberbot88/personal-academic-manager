<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'storage_driver',
        'storage_path',
        'original_name',
        'mime_type',
        'size_bytes',
        'checksum_sha256',
        'uploaded_at',
        'label',
        'note',
        'attachment_group_id',
        'version_number',
        'is_current',
        'is_final',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_current' => 'boolean',
        'is_final' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(AttachmentGroup::class, 'attachment_group_id');
    }

    // Polymorphic relations
    public function materials(): MorphToMany
    {
        return $this->morphedByMany(Material::class, 'attachmentable', 'attachmentables')
            ->withPivot('role');
    }

    public function tasks(): MorphToMany
    {
        return $this->morphedByMany(Task::class, 'attachmentable', 'attachmentables')
            ->withPivot('role');
    }

    // Helper methods
    public function getDownloadResponse()
    {
        if ($this->storage_driver === 'r2') {
            /** @var \Aws\S3\S3Client $s3 */
            $s3 = \App\Support\R2ClientFactory::make();
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => config('pam.r2.bucket'),
                'Key' => $this->storage_path,
                'ResponseContentDisposition' => 'attachment; filename="' . $this->original_name . '"',
            ]);

            $request = $s3->createPresignedRequest($cmd, '+15 minutes');
            return redirect((string) $request->getUri());
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->storage_driver === 'local_private' ? 'private' : $this->storage_driver);

        return $disk->download($this->storage_path, $this->original_name);
    }

    public function calculateChecksum(): string
    {
        $disk = Storage::disk($this->storage_driver === 'local_private' ? 'private' : $this->storage_driver);

        return hash('sha256', $disk->get($this->storage_path));
    }

    public function getHumanReadableSizeAttribute(): string
    {
        if (!$this->size_bytes) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->size_bytes;

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
