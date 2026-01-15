<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach(['slot1', 'slot2', 'slot3'] as $slot)
            @php
                $task = $top3[$slot];
                $reason = $selector->getReason($task, $slot);
                // Accent colors for slots
                $accentColor = match ($slot) {
                    'slot1' => 'border-primary-500 text-primary-600',
                    'slot2' => 'border-gray-400 text-gray-500',
                    'slot3' => 'border-danger-500 text-danger-600',
                };
            @endphp

            @if($task)
                {{-- Active Card --}}
                <div
                    class="relative flex flex-col h-full bg-white dark:bg-gray-900 rounded-2xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden group transition hover:shadow-md">

                    {{-- Color Accent Top --}}
                    <div class="absolute top-0 left-0 w-full h-1 {{ explode(' ', $accentColor)[0] }} bg-current opacity-75">
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        {{-- Header / Reason --}}
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-widest {{ explode(' ', $accentColor)[1] }}">
                                {{ $reason }}
                            </span>
                            @if($task->priority_boost)
                                <x-filament::icon icon="heroicon-m-bolt" class="w-5 h-5 text-warning-500 animate-pulse" />
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="mb-auto">
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2 line-clamp-1">
                                {{ $task->primaryCourse->name ?? 'No Course' }}
                            </div>
                            <h3
                                class="text-2xl font-bold text-gray-900 dark:text-white mb-2 leading-tight tracking-tight line-clamp-2">
                                {{ $task->title }}
                            </h3>

                            {{-- Next Action --}}
                            @php
                                $nextAction = 'Tentukan next action (≤30 menit)';
                                // Find active phase (not 100%)
                                $activePhase = $task->taskPhases->where('progress_pct', '<', 100)->sortBy('sort_order')->first();
                                if ($activePhase) {
                                    $nextItem = $activePhase->checklistItems->where('is_completed', false)->first();
                                    if ($nextItem) {
                                        $nextAction = $nextItem->content;
                                    } else {
                                        $nextAction = "Selesaikan fase: " . $activePhase->name;
                                    }
                                }
                            @endphp
                            <div class="mb-4">
                                <div class="text-xs font-bold text-gray-500 uppercase mb-1">Next Action:</div>
                                <div
                                    class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 p-2 rounded border border-gray-200 dark:border-gray-700">
                                    <x-filament::icon icon="heroicon-m-chevron-right" class="w-4 h-4 text-primary-500 mt-0.5" />
                                    <span class="line-clamp-2 leading-snug">{{ $nextAction }}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <div
                                    class="flex items-center text-sm font-medium {{ $task->effective_due && $task->effective_due->isPast() ? 'text-danger-600' : 'text-gray-500' }}">
                                    <x-filament::icon icon="heroicon-m-calendar" class="w-4 h-4 mr-1.5" />
                                    {{ $task->effective_due ? $task->effective_due->format('d M') : '-' }}
                                    @if($task->nearest_phase_due)
                                        <span class="text-xs ml-1 text-gray-400">(Phase)</span>
                                    @endif
                                </div>

                                <x-filament::badge size="xs" :color="$task->health_status === 'aman' ? 'success' : ($task->health_status === 'rawan' ? 'warning' : 'danger')">
                                    {{ $task->progress_pct }}%
                                </x-filament::badge>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                            @if(!$task->first_touched_at)
                                <x-filament::button wire:click="markStarted({{ $task->id }})" color="primary" size="sm"
                                    icon="heroicon-m-play">
                                    Mulai
                                </x-filament::button>
                            @else
                                <x-filament::button wire:click="openTask({{ $task->id }})" color="gray" outlined size="sm">
                                    Buka
                                </x-filament::button>
                            @endif

                            <x-filament::button wire:click="markDone({{ $task->id }})" color="success" size="sm"
                                icon="heroicon-m-check">
                                Selesai
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty State Card --}}
                <div
                    class="flex flex-col items-center justify-center h-full p-8 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 text-center opacity-75">
                    <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center mb-4">
                        <x-filament::icon icon="heroicon-o-check" class="w-6 h-6 text-gray-400" />
                    </div>
                    <p class="text-gray-500 font-medium mb-1">Slot Kosong</p>
                    <p class="text-xs text-gray-400 max-w-[150px]">Tidak ada tugas tugas yang prioritas untuk slot ini.</p>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <x-filament::button tag="a" href="/admin/tasks" size="lg" color="gray" outlined>
            Lihat Semua Tugas
        </x-filament::button>
    </div>
</x-filament-panels::page>