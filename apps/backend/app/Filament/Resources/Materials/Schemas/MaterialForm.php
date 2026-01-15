<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Material Library')
                    ->description('Kelola materi pembelajaran (note/link/file)')
                    ->schema([
                        Select::make('course_id')
                            ->label('Mata Kuliah')
                            ->relationship('course', 'name')
                            ->searchable()
                            ->required()
                            ->columnSpan(1),

                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'note' => 'Note/Catatan',
                                'link' => 'Link/URL',
                                'file' => 'File',
                            ])
                            ->required()
                            ->default('link')
                            ->live()
                            ->columnSpan(1),

                        \Filament\Forms\Components\FileUpload::make('attachment_file')
                            ->label('Upload File')
                            ->disk('private')
                            ->directory('materials/' . date('Y/m'))
                            ->preserveFilenames()
                            ->maxSize(51200) // 50MB
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip', 'image/*'])
                            ->visible(fn($get) => $get('type') === 'file')
                            ->dehydrated(false) // Don't save to material table
                            ->columnSpanFull(),

                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required(fn($get) => $get('type') === 'link')
                            ->visible(fn($get) => in_array($get('type'), ['link', null]))
                            ->columnSpanFull(),

                        Textarea::make('note')
                            ->label('Catatan/Note')
                            ->required(fn($get) => $get('type') === 'note')
                            ->rows(5)
                            ->columnSpanFull(),

                        TagsInput::make('tags')
                            ->label('Tags')
                            ->separator(',')
                            ->dehydrated(false) // Handle in Create/Edit Page lifecycle
                            ->columnSpanFull(),

                        Select::make('source')
                            ->label('Sumber')
                            ->options([
                                'WA' => 'WhatsApp',
                                'LMS' => 'LMS/E-Learning',
                                'Drive' => 'Google Drive',
                                'Manual' => 'Manual',
                                'Other' => 'Lainnya',
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
