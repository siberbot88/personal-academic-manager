<?php

namespace App\Filament\Resources\PhaseTemplateResource\Pages;

use App\Filament\Resources\PhaseTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhaseTemplates extends ListRecords
{
    protected static string $resource = PhaseTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
