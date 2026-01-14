<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Tags\Tag;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('title')
                        ->label('Judul')
                        ->searchable()
                        ->sortable()
                        ->limit(50)
                        ->weight('bold'),

                    TextColumn::make('course.name')
                        ->label('Mata Kuliah')
                        ->sortable()
                        ->searchable()
                        ->badge()
                        ->color('primary'),

                    TextColumn::make('type')
                        ->label('Tipe')
                        ->badge()
                        ->color(fn($record) => match ($record->type) {
                            'note' => 'warning',
                            'link' => 'info',
                            'file' => 'success',
                            default => 'gray',
                        }),

                    TextColumn::make('tags.name')
                        ->label('Tags')
                        ->badge()
                        ->separator(',')
                        ->color('success')
                        ->limit(3),

                    TextColumn::make('source')
                        ->label('Sumber')
                        ->badge()
                        ->color('gray'),

                    TextColumn::make('captured_at')
                        ->label('Ditangkap')
                        ->dateTime('d M Y')
                        ->sortable()
                        ->since(),
                ])
            ->filters([
                    SelectFilter::make('course_id')
                        ->label('Mata Kuliah')
                        ->relationship('course', 'name')
                        ->searchable(),

                    SelectFilter::make('type')
                        ->label('Tipe')
                        ->options([
                                'note' => 'Note',
                                'link' => 'Link',
                                'file' => 'File',
                            ]),

                    SelectFilter::make('tags')
                        ->label('Tag')
                        ->multiple()
                        ->options(fn() => Tag::pluck('name', 'name')->toArray()),
                ])
            ->actions([
                    EditAction::make(),
                ])
            ->bulkActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ])
            ->defaultSort('captured_at', 'desc');
    }
}
