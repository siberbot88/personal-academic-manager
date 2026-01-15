<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use App\Models\StudySession;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class StudySessionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Sesi Belajar';
    protected static ?string $title = 'Log Sesi Belajar';
    protected static ?string $slug = 'study-sessions';
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.study-session-page';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StudyQuickLogWidget::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StudySession::query()->where('user_id', auth()->id())->latest('started_at'))
            ->columns([
                TextColumn::make('started_at')->dateTime('D, d M H:i')->label('Waktu'),
                TextColumn::make('duration_min')->suffix(' m')->label('Durasi'),
                TextColumn::make('course.name')->label('Course')->placeholder('-'),
                TextColumn::make('task.title')->label('Task')->placeholder('-')->limit(30),
                TextColumn::make('mode')->badge()->color(fn(string $state): string => match ($state) {
                    'study' => 'success',
                    'review' => 'warning',
                    'writing' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('note')->limit(50),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        Select::make('mode')->options([
                            'study' => 'Belajar',
                            'review' => 'Review',
                            'writing' => 'Nulis/Nugas',
                        ])->required(),
                        Textarea::make('note')->rows(3),
                    ]),
                DeleteAction::make(),
            ]);
    }
}
