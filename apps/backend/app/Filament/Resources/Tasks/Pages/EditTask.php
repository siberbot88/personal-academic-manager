<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('regeneratePhases')
                ->label('Regenerate Phases')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    app(\App\Services\TaskPhaseGenerator::class)->generate($this->getRecord());
                    \Filament\Notifications\Notification::make()
                        ->title('Phases Regenerated')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
