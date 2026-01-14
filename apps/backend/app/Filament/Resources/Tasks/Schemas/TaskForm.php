<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                TextInput::make('progress')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->label('Progress'),
            ]);
    }
}
