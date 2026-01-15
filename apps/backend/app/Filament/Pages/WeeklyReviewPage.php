<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\StudyStatsService;
use App\Models\Task;
use App\Models\WeeklyPlan;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class WeeklyReviewPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Weekly Review';
    protected static ?string $title = 'Weekly Review';
    protected static ?string $slug = 'weekly-review';
    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.weekly-review-page';

    // Stats
    public array $consistency = [];
    public array $startEarly = [];
    public array $execution = [];

    // Form Data
    public ?array $data = [];

    public function mount()
    {
        $this->calculateStats();

        // Load existing plan if any
        $plan = WeeklyPlan::where('user_id', auth()->id())
            ->where('week_start', now()->startOfWeek()->format('Y-m-d'))
            ->first();

        if ($plan) {
            $this->form->fill([
                'focus_task_ids' => $plan->focus_task_ids,
                'note' => $plan->note,
            ]);
        }
    }

    protected function calculateStats()
    {
        $stats = new StudyStatsService();
        $this->consistency = $stats->progressToWeeklyTarget(5);
        $this->consistency['streak'] = $stats->currentDailyStreak(); // Ensure this method exists in service

        // Start Early Metric
        // Tasks due next 14 days or valid set
        // Count tasks started before H-7
        $tasksWithDueDate = Task::whereNotNull('due_date')
            // ->where('status', '!=', 'archived') // Consider all relevant tasks
            ->where('due_date', '>=', now()->subDays(30)) // Check recent history too
            ->get();

        $startedEarlyCount = $tasksWithDueDate->filter(fn($t) => $t->started_before_h7)->count();
        $totalEvaluated = $tasksWithDueDate->count();

        $this->startEarly = [
            'percentage' => $totalEvaluated > 0 ? round(($startedEarlyCount / $totalEvaluated) * 100) : 0,
            'high_risk_tasks' => Task::whereNotNull('due_date')
                ->whereNull('first_touched_at')
                ->where('due_date', '<=', now()->addDays(7))
                ->where('due_date', '>=', now())
                ->where('status', '!=', 'done')
                ->limit(3)
                ->get(),
        ];

        // Execution
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $this->execution = [
            'done_this_week' => Task::where('status', 'done')
                ->whereBetween('updated_at', [$startOfWeek, $endOfWeek]) // Approx
                ->count(),
            'overdue' => Task::where('due_date', '<', now())
                ->where('status', '!=', 'done')
                ->where('status', '!=', 'archived')
                ->count(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rencana Minggu Depan')
                    ->schema([
                        Select::make('focus_task_ids')
                            ->label('3 Fokus Utama')
                            ->multiple()
                            ->maxItems(3)
                            ->options(Task::where('status', '!=', 'done')->where('status', '!=', 'archived')->pluck('title', 'id'))
                            ->searchable(),
                        Textarea::make('note')
                            ->label('Catatan / Komitmen')
                            ->rows(2),
                    ])
            ])
            ->statePath('data');
    }

    public function submitPlan()
    {
        $data = $this->form->getState();

        WeeklyPlan::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'week_start' => now()->startOfWeek()->format('Y-m-d'),
            ],
            [
                'focus_task_ids' => $data['focus_task_ids'],
                'note' => $data['note'],
            ]
        );

        Notification::make()
            ->title('Rencana tersimpan!')
            ->success()
            ->send();
    }
}
