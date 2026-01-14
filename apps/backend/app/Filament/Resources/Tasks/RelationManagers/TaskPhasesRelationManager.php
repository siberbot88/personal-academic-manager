<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TaskPhasesRelationManager extends RelationManager
{
    protected static string $relationship = 'taskPhases';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('due_date'),
                    DatePicker::make('start_date'),
                    Select::make('status')
                        ->options([
                                'todo' => 'To Do',
                                'doing' => 'Doing',
                                'done' => 'Done',
                            ]),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                    TextColumn::make('title'),
                    TextColumn::make('due_date')->date(),
                    TextColumn::make('status')->badge(),
                    TextColumn::make('progress_pct')->label('%')->suffix('%'),
                    TextColumn::make('checklistItems_count')->counts('checklistItems')->label('Items'),
                ])
            ->filters([
                    //
                ])
            ->headerActions([
                    // CreateAction::make(),
                ])
            ->actions([
                    // EditAction::make(),
                    // DeleteAction::make(),
                ])
            ->bulkActions([
                    // BulkActionGroup::make([
                    //    DeleteBulkAction::make(),
                    // ]),
                ])
            ->defaultSort('sort_order', 'asc');
    }
}
