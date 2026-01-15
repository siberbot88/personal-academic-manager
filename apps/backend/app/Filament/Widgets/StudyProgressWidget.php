<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\StudyStatsService;

class StudyProgressWidget extends Widget
{
    protected string $view = 'filament.widgets.study-progress-widget';
    protected int|string|array $columnSpan = 1;

    public array $stats = [];

    public function mount()
    {
        $service = new StudyStatsService();
        $this->stats = $service->progressToWeeklyTarget();
    }
}
