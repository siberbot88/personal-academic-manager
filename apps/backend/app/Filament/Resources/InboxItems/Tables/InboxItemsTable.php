<?php

namespace App\Filament\Resources\InboxItems\Tables;

use App\Filament\Resources\Materials\MaterialResource;
use App\Services\InboxPromoter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                    SelectFilter::make('status')
                        ->options([
                                'inbox' => 'Inbox',
                                'promoted' => 'Promoted',
                                'archived' => 'Archived',
                            ])
                        ->default('inbox'),

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

                    Action::make('promote')
                        ->label('Promote')
                        ->icon('heroicon-o-arrow-up-tray')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'inbox')
                        ->form(fn($record) => [
                            Select::make('type')
                                ->options([
                                        'note' => 'Note',
                                        'link' => 'Link',
                                        'file' => 'File',
                                    ])
                                ->default($record->type ?? 'link')
                                ->required(),
                            TextInput::make('title')
                                ->default($record->title)
                                ->required(),
                            Textarea::make('note')
                                ->default($record->note),
                        ])
                        ->action(function (array $data, $record) {
                            $promoter = new InboxPromoter();
                            $material = $promoter->promoteToMaterial($record, [
                                'type' => $data['type'],
                                'title' => $data['title'],
                                'note' => $data['note'],
                                'task_ids' => $record->task_id ? [$record->task_id] : [],
                            ]);

                            Notification::make()->success()->title('Promoted to Material')->send();

                            return redirect()->to(MaterialResource::getUrl('edit', ['record' => $material]));
                        }),

                    Action::make('archive')
                        ->label('Archive')
                        ->icon('heroicon-o-archive-box')
                        ->color('gray')
                        ->visible(fn($record) => $record->status === 'inbox')
                        ->action(fn($record) => $record->update(['status' => 'archived']))
                        ->requiresConfirmation(),

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
