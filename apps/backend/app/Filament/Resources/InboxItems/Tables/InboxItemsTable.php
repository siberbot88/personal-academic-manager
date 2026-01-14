<?php

namespace App\Filament\Resources\InboxItems\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Tags\Tag;

class InboxItemsTable
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

                    TextColumn::make('task.title')
                        ->label('Tugas')
                        ->placeholder('—')
                        ->limit(30)
                        ->color('gray'),

                    TextColumn::make('tags.name')
                        ->label('Tags')
                        ->badge()
                        ->separator(',')
                        ->color('success'),

                    TextColumn::make('source')
                        ->label('Sumber')
                        ->badge()
                        ->color(fn($record) => match ($record->source) {
                            'WA' => 'success',
                            'LMS' => 'info',
                            'Drive' => 'warning',
                            default => 'gray',
                        }),

                    TextColumn::make('captured_at')
                        ->label('Ditangkap')
                        ->dateTime('d M Y, H:i')
                        ->sortable()
                        ->since(),
                ])
            ->filters([
                    SelectFilter::make('course_id')
                        ->label('Mata Kuliah')
                        ->relationship('course', 'name')
                        ->searchable(),

                    SelectFilter::make('tags')
                        ->label('Tag')
                        ->multiple()
                        ->options(fn() => Tag::pluck('name', 'name')->toArray()),

                    SelectFilter::make('source')
                        ->label('Sumber')
                        ->options([
                                'WA' => 'WhatsApp',
                                'LMS' => 'LMS/E-Learning',
                                'Drive' => 'Google Drive',
                                'Other' => 'Lainnya',
                            ]),
                ])
            ->actions([
                    Action::make('open')
                        ->label('Open Link')
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn($record) => $record->url)
                        ->openUrlInNewTab()
                        ->color('primary'),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ->bulkActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ])
            ->defaultSort('captured_at', 'desc');
    }
}
