<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Resources\Tasks\TaskResource;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Fokus Utama';
    protected string $view = 'filament.pages.dashboard';

    // Override standard Dashboard logic if inheriting? No, Dashboard is usually empty.
    // We generated 'Page' which is generic.
    // If we want it to BE the dashboard, safe to assume it's just a Page registered as Dashboard::class in Panel.

    public function getViewData(): array
    {
        $selector = app(\App\Services\Top3Selector::class);
        $top3 = $selector->getTop3();

        return [
            'top3' => $top3,
            'selector' => $selector,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StudyProgressWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('log_session')
                ->label('Log Sesi')
                ->icon('heroicon-m-clock')
                ->color('primary')
                ->form([
                    \Filament\Forms\Components\Select::make('course_id')
                        ->label('Course')
                        ->options(\App\Models\Course::pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Pilih Mata Kuliah'),
                    \Filament\Forms\Components\Select::make('task_id')
                        ->label('Task')
                        ->options(\App\Models\Task::where('status', '!=', 'archived')->pluck('title', 'id'))
                        ->searchable()
                        ->placeholder('Pilih Tugas'),
                    \Filament\Forms\Components\ToggleButtons::make('duration_min')
                        ->label('Durasi')
                        ->options([
                            25 => '25m (Pomodoro)',
                            50 => '50m (Deep Work)',
                            120 => '120m (Marathon)',
                        ])
                        ->default(50)
                        ->inline()
                        ->required(),
                    \Filament\Forms\Components\ToggleButtons::make('mode')
                        ->label('Mode')
                        ->options([
                            'study' => 'Belajar',
                            'review' => 'Review',
                            'writing' => 'Nulis',
                        ])
                        ->default('study')
                        ->inline()
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('note')
                        ->label('Catatan')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    $session = \App\Models\StudySession::create([
                        'user_id' => auth()->id(),
                        'course_id' => $data['course_id'] ?? null,
                        'task_id' => $data['task_id'] ?? null,
                        'started_at' => now()->subMinutes($data['duration_min']),
                        'ended_at' => now(),
                        'duration_min' => $data['duration_min'],
                        'mode' => $data['mode'],
                        'note' => $data['note'],
                    ]);

                    if ($session->task_id && $session->task) {
                        $session->task->markAsStarted();
                    }

                    \Filament\Notifications\Notification::make()
                        ->title("Sesi {$data['duration_min']} menit tercatat!")
                        ->success()
                        ->send();

                    // Refresh widgets? Livewire page reloads?
                }),

            \Filament\Actions\Action::make('create_task')
                ->label('Buat Task')
                ->icon('heroicon-m-plus')
                ->color('gray')
                ->url(\App\Filament\Resources\Tasks\TaskResource::getUrl('create')),
        ];
    }

    public function markStarted(int $taskId)
    {
        $task = \App\Models\Task::find($taskId);
        if ($task) {
            $task->markAsStarted();

            \Filament\Notifications\Notification::make()
                ->title('Task Dimulai!')
                ->success()
                ->send();
        }
    }

    public function markDone(int $taskId)
    {
        $task = \App\Models\Task::find($taskId);
        if ($task) {
            $task->update(['status' => 'Done']);

            // HealthScorer reset logic is handled by TaskObserver or Scorer itself?
            // Week 6 implemented logic: TaskObserver triggers HealthScorer->recalcTask on status change.
            // HealthScorer handles Done state by resetting flags.
            // So just updating status here is safe.

            \Filament\Notifications\Notification::make()
                ->title('Task Selesai')
                ->success()
                ->send();

            // Redirect to refresh? Livewire should auto-refresh view if data changes.
            // But getTop3() is called on render? Filament Page data binding...
            // getViewData is passed to view. Does it refresh on action? 
            // Standard Livewire component behaviour: re-renders view.
            // But getViewData might only run on mount?
            // Filament Pages use $this->viewData in render().
            // We should use viewData property or just pass in render()?
            // Filament 3 Pages: extends Component. render() returns view($view, $this->getViewData()).
            // So re-render should pick up new Top 3.
        }
    }



    public function openTask(int $taskId)
    {
        return redirect()->to(TaskResource::getUrl('edit', ['record' => $taskId]));
    }
}
