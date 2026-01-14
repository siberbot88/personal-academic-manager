<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Info Inti')
                        ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Judul Tugas'),

                                Select::make('primary_course_id')
                                    ->relationship('primaryCourse', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Mata Kuliah'),
                            ])->columns(2),

                    Section::make('Detail & Jadwal')
                        ->schema([
                                Select::make('task_type_template_id')
                                    ->relationship('taskTypeTemplate', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Pilih template untuk auto-generate fase (misal: Laporan Akhir).')
                                    ->label('Jenis Tugas (Template)'),

                                Select::make('size')
                                    ->options([
                                            'auto' => 'Auto (Based on Duration)',
                                            'small' => 'Small',
                                            'big' => 'Big',
                                        ])
                                    ->default('auto')
                                    ->required()
                                    ->helperText('Auto: Menghitung ukuran berdasarkan durasi deadline.')
                                    ->label('Ukuran Tugas'),

                                DatePicker::make('due_date')
                                    ->nullable()
                                    ->label('Tanggal Deadline'),

                                Select::make('status')
                                    ->options([
                                            'Active' => 'Active',
                                            'Done' => 'Done',
                                            'Archived' => 'Archived',
                                        ])
                                    ->default('Active')
                                    ->required()
                                    ->label('Status'),

                                TextInput::make('progress_pct')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->suffix('%')
                                    ->label('Progress (Auto-calc)'),
                            ])->columns(2),
                ]);
    }
}
