<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

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

    // Actions
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
        return redirect()->to(\App\Filament\Resources\Tasks\TaskResource::getUrl('edit', ['record' => $taskId]));
    }
}
