<?php

namespace App\Filament\Resources\TaskTypeTemplateResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PhasesRelationManager extends RelationManager
{
    protected static string $relationship = 'phases';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextInput::make('weight_percent')
                        ->required()
                        ->numeric()
                        ->label('Weight (%)')
                        ->maxValue(100),
                    TextInput::make('sort_order')
                        ->required()
                        ->numeric()
                        ->default(0),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                    TextColumn::make('name'),
                    TextColumn::make('weight_percent'),
                    TextColumn::make('sort_order')->sortable(),
                ])
            ->filters([
                    //
                ])
            ->headerActions([
                    AttachAction::make()
                        ->preloadRecordSelect()
                        ->form(fn(AttachAction $action): array => [
                            $action->getRecordSelect(),
                            TextInput::make('weight_percent')->required()->numeric(),
                            TextInput::make('sort_order')->required()->numeric()->default(0),
                        ]),
                ])
            ->actions([
                    EditAction::make(),
                    DetachAction::make(),
                ])
            ->bulkActions([
                    BulkActionGroup::make([
                        DetachBulkAction::make(),
                    ]),
                ])
            ->defaultSort('sort_order', 'asc');
    }
}
