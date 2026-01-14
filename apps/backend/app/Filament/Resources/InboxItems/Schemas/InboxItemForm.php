<?php

namespace App\Filament\Resources\InboxItems\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InboxItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Quick Capture')
                        ->description('Simpan link penting dari WA/Drive (<30 detik)')
                        ->schema([
                                Select::make('course_id')
                                    ->label('Mata Kuliah')
                                    ->relationship('course', 'name')
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(1),

                                Select::make('task_id')
                                    ->label('Tugas (Optional)')
                                    ->relationship('task', 'title')
                                    ->searchable()
                                    ->columnSpan(1),

                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('url')
                                    ->label('Link/URL')
                                    ->url()
                                    ->required()
                                    ->columnSpanFull()
                                    ->placeholder('https://...'),

                                TagsInput::make('tags')
                                    ->label('Tags')
                                    ->separator(',')
                                    ->columnSpanFull(),

                                Textarea::make('note')
                                    ->label('Catatan')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Select::make('source')
                                    ->label('Sumber')
                                    ->options([
                                            'WA' => 'WhatsApp',
                                            'LMS' => 'LMS/E-Learning',
                                            'Drive' => 'Google Drive',
                                            'Other' => 'Lainnya',
                                        ])
                                    ->columnSpan(1),
                            ])
                        ->columns(2),
                ]);
    }
}
