<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract file path before create to avoid DB column error
        if (isset($data['attachment_file'])) {
            $this->uploadedFilePath = $data['attachment_file'];
            unset($data['attachment_file']);
        }

        return $data;
    }

    protected ?string $uploadedFilePath = null;

    protected function afterCreate(): void
    {
        // Handle tags
        if ($this->data['tags'] ?? null) {
            $this->record->syncTags($this->data['tags']);
        }

        // Handle inline file upload
        if ($this->uploadedFilePath) {
            $filePath = $this->uploadedFilePath;
            $disk = 'private';

            // Create Attachment record
            $attachment = \App\Models\Attachment::create([
                'original_name' => basename($filePath),
                'file_path' => $filePath,
                'disk' => $disk,
                'size_bytes' => \Storage::disk($disk)->size($filePath),
                'mime_type' => \Storage::disk($disk)->getMimeType($filePath),
                'uploaded_at' => now(),
                'uploaded_by' => auth()->id(),
                'version_number' => 1,
                'is_current' => true,
                'is_final' => false,
                'attachment_group_id' => \Str::uuid(),
            ]);

            // Attach to Material
            $this->record->attachments()->attach($attachment->id, [
                'role' => 'primary',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
