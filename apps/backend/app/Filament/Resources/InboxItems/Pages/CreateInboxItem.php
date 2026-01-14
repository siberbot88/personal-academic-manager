<?php

namespace App\Filament\Resources\InboxItems\Pages;

use App\Filament\Resources\InboxItems\InboxItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInboxItem extends CreateRecord
{
    protected static string $resource = InboxItemResource::class;

    protected function afterCreate(): void
    {
        if ($this->data['tags'] ?? null) {
            $this->record->syncTags($this->data['tags']);
        }
    }
}
