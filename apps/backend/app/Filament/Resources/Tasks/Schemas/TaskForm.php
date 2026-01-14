<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('primary_course_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('due_date'),
                TextInput::make('status')
                    ->required()
                    ->default('Active'),
                TextInput::make('progress')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('type_template_id')
                    ->numeric(),
            ]);
    }
}
