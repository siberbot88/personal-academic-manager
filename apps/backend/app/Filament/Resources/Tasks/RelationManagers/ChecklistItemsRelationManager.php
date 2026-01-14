<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'checklistItems';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->orderBy('task_phases.sort_order')
                    ->orderBy('checklist_items.sort_order')
                    ->select('checklist_items.*')
            )
            ->defaultGroup('taskPhase.title')
            ->recordTitleAttribute('title')
            ->columns([
                    TextColumn::make('title')
                        ->label('Kegiatan')
                        ->searchable(),
                    ToggleColumn::make('is_done')
                        ->label('Selesai')
                        ->afterStateUpdated(function ($record, $state) {
                            $record->done_at = $state ? now() : null;
                            $record->save();
                        }),
                ])
            ->filters([
                    //
                ])
            ->headerActions([
                    CreateAction::make()->label('Tambah Item Manual'),
                ])
            ->actions([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ->bulkActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ]);
    }
}
