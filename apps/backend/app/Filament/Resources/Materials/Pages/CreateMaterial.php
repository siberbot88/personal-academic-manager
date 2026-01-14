<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected function afterCreate(): void
    {
        if ($this->data['tags'] ?? null) {
            $this->record->syncTags($this->data['tags']);
        }
    }
}
