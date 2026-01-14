<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul Tugas'),

                TextColumn::make('primaryCourse.name')
                    ->searchable()
                    ->sortable()
                    ->label('Mata Kuliah'),

                TextColumn::make('due_date')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Deadline'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Active' => 'warning',
                        'Done' => 'success',
                        'Archived' => 'gray',
                    })
                    ->sortable()
                    ->label('Status'),

                TextColumn::make('progress')
                    ->suffix('%')
                    ->sortable()
                    ->label('Progress'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Done' => 'Done',
                        'Archived' => 'Archived',
                    ])
                    ->label('Status'),

                SelectFilter::make('primary_course_id')
                    ->relationship('primaryCourse', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Mata Kuliah'),
            ])
            ->defaultSort('due_date', 'asc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
