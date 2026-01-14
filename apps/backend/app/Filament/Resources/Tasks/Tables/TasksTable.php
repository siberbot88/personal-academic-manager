<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\Action;
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

                    TextColumn::make('progress_pct')
                        ->label('Progress')
                        ->badge()
                        ->color(fn($state) => $state >= 100 ? 'success' : ($state > 50 ? 'warning' : 'gray'))
                        ->formatStateUsing(fn($state) => "{$state}%")
                        ->sortable(),


                    TextColumn::make('health_status')
                        ->badge()
                        ->color(fn(string $state): string => match ($state) {
                            'aman' => 'success',
                            'rawan' => 'warning',
                            'bahaya' => 'danger',
                        })
                        ->label('Health'),

                    TextColumn::make('health_score')
                        ->numeric()
                        ->sortable()
                        ->label('Score')
                        ->color(fn($state) => $state < 40 ? 'danger' : ($state < 70 ? 'warning' : 'success')),

                    TextColumn::make('stagnation_days')
                        ->label('Stagnasi (Hari)')
                        ->toggleable(isToggledHiddenByDefault: true),

                    \Filament\Tables\Columns\IconColumn::make('attention_flag')
                        ->boolean()
                        ->trueIcon('heroicon-o-exclamation-triangle')
                        ->trueColor('danger')
                        ->falseIcon('')
                        ->label('Flag'),

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
                        ->default('Active')
                        ->label('Status'),

                    SelectFilter::make('primary_course_id')
                        ->relationship('primaryCourse', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Mata Kuliah'),

                    SelectFilter::make('health_status')
                        ->options([
                                'aman' => 'Aman',
                                'rawan' => 'Rawan',
                                'bahaya' => 'Bahaya',
                            ])
                        ->label('Kesehatan Task'),

                    \Filament\Tables\Filters\Filter::make('attention_flag')
                        ->query(fn($query) => $query->where('attention_flag', true))
                        ->label('Perlu Perhatian'),
                ])
            ->defaultSort(fn($query) => $query->orderBy('priority_boost', 'desc')->orderBy('due_date', 'asc'))
            ->recordActions([
                    EditAction::make(),
                    Action::make('markDone')
                        ->label('Selesai')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status !== 'Done')
                        ->action(fn($record) => $record->update(['status' => 'Done'])),
                    Action::make('archive')
                        ->label('Arsip')
                        ->icon('heroicon-o-archive-box')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status !== 'Archived')
                        ->action(fn($record) => $record->update(['status' => 'Archived'])),

                    Action::make('clearBoost')
                        ->label('Clear Boost')
                        ->icon('heroicon-o-bolt-slash')
                        ->color('gray')
                        ->visible(fn($record) => $record->priority_boost)
                        ->action(fn($record) => $record->update(['priority_boost' => false])),
                ])
            ->emptyStateHeading('Belum ada tugas. Buat dari template!')
            ->toolbarActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ]);
    }
}
