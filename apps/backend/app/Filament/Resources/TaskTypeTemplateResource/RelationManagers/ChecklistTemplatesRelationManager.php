<?php

namespace App\Filament\Resources\TaskTypeTemplateResource\RelationManagers;

use App\Models\PhaseTemplate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class ChecklistTemplatesRelationManager extends RelationManager
{
    protected static string $relationship = 'checklistTemplates';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Select::make('phase_template_id')
                        ->label('Phase')
                        ->options(PhaseTemplate::where('is_active', true)->pluck('name', 'id'))
                        ->required(),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->default(true),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                    TextColumn::make('title')->searchable(),
                    TextColumn::make('phaseTemplate.name')->label('Phase')->sortable(),
                    TextColumn::make('sort_order')->sortable(),
                    ToggleColumn::make('is_active'),
                ])
            ->filters([
                    //
                ])
            ->headerActions([
                    CreateAction::make(),
                ])
            ->actions([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ->bulkActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ])
            ->defaultSort('sort_order', 'asc');
    }
}
