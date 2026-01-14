<?php

namespace App\Filament\Resources\TaskTypeTemplateResource\Pages;

use App\Filament\Resources\TaskTypeTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskTypeTemplate extends EditRecord
{
    protected static string $resource = TaskTypeTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
