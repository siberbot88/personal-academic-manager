<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\StudySession;
use Filament\Notifications\Notification;
use App\Models\Course;
use App\Models\Task;
use App\Services\StudyStatsService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextTextInput;
use Filament\Forms\Components\TextInput;

class StudyQuickLogWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.study-quick-log-widget';
    protected int|string|array $columnSpan = 'full';

    public ?int $course_id = null;
    public ?int $task_id = null;
    public string $mode = 'study';
    public ?string $note = null;

    // Stats for display
    public int $todaysCount = 0;
    public int $streak = 0;

    public function mount() // No arguments for mount in Widget? Actually it works.
    {
        $this->refreshStats();
        $this->form->fill();
    }

    public function refreshStats()
    {
        $stats = new StudyStatsService();
        $this->streak = $stats->currentDailyStreak();
        // Simple daily count
        $this->todaysCount = StudySession::where('user_id', auth()->id())
            ->whereDate('started_at', today())
            ->count();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('course_id')
                ->label('Course')
                ->options(Course::pluck('name', 'id'))
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn($state) => $this->course_id = $state)
                ->placeholder('Pilih Mata Kuliah (Opsional)'),

            Select::make('task_id')
                ->label('Task')
                ->options(function (callable $get) {
                    $query = Task::where('status', '!=', 'archived');
                    if ($get('course_id')) {
                        $query->where('primary_course_id', $get('course_id'));
                    }
                    return $query->pluck('title', 'id');
                })
                ->searchable()
                ->placeholder('Pilih Tugas (Opsional)'),
        ];
    }

    public function logSession(int $minutes)
    {
        $data = $this->form->getState();

        $session = StudySession::create([
            'user_id' => auth()->id(),
            'course_id' => $data['course_id'] ?? null,
            'task_id' => $data['task_id'] ?? null,
            'started_at' => now()->subMinutes($minutes),
            'ended_at' => now(),
            'duration_min' => $minutes,
            'mode' => $this->mode,
            'note' => $this->note,
        ]);

        // If task is linked, mark as started
        if ($session->task_id && $session->task) {
            $session->task->markAsStarted();
        }

        Notification::make()
            ->title("Sesi $minutes menit tercatat!")
            ->success()
            ->send();

        $this->refreshStats();

        // Reset form or keep context? Better keep for streaks, but maybe reset task?
        // Let's reset task but keep course?
        // For simple UX, keep everything.

        $this->dispatch('session-logged'); // Event listener to refresh table?
    }

    public function setMode(string $mode)
    {
        $this->mode = $mode;
    }
}
