<?php

namespace App\Filament\Resources\TaskTypeTemplateResource\Pages;

use App\Filament\Resources\TaskTypeTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskTypeTemplates extends ListRecords
{
    protected static string $resource = TaskTypeTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
