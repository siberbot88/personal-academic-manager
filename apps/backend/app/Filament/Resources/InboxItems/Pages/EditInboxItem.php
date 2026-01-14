<?php

namespace App\Filament\Resources\InboxItems\Pages;

use App\Filament\Resources\InboxItems\InboxItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInboxItem extends EditRecord
{
    protected static string $resource = InboxItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load tags into form
        $data['tags'] = $this->record->tags->pluck('name')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        // Sync tags after save
        if ($this->data['tags'] ?? null) {
            $this->record->syncTags($this->data['tags']);
        } else {
            $this->record->detachTags($this->record->tags);
        }
    }
}
