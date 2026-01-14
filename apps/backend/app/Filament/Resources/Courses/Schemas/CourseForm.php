<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Select::make('semester_id')
                        ->relationship('semester', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('name')
                        ->required(),
                ]);
    }
}
