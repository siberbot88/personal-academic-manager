<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tags'] = $this->record->tags->pluck('name')->toArray();
        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->data['tags'] ?? null) {
            $this->record->syncTags($this->data['tags']);
        } else {
            $this->record->detachTags($this->record->tags);
        }
    }
}
