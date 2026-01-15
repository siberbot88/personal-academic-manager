<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">Target Belajar (5 Sesi)</h2>
            <span class="text-xs font-bold px-2 py-1 rounded bg-gray-100 dark:bg-gray-700">
                Week {{ now()->weekOfYear }}
            </span>
        </div>

        <div class="mt-4 flex items-end justify-between">
            <div>
                <span class="text-3xl font-black {{ $stats['achieved'] ? 'text-green-600' : 'text-primary-600' }}">
                    {{ $stats['count'] }}
                </span>
                <span class="text-gray-400 text-sm">/ {{ $stats['target'] }}</span>
            </div>

            <div class="flex flex-col items-end">
                @if($stats['achieved'])
                    <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                        TARGET REACHED! <x-filament::icon icon="heroicon-m-trophy" class="w-4 h-4" />
                    </span>
                @else
                    <span class="text-xs text-gray-500">{{ $stats['remaining'] }} lagi to go!</span>
                @endif
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mt-3 h-2 w-full bg-gray-100 rounded-full overflow-hidden dark:bg-gray-700">
            <div class="h-full {{ $stats['achieved'] ? 'bg-green-500' : 'bg-primary-500' }} transition-all duration-500"
                style="width: {{ min(100, ($stats['count'] / $stats['target']) * 100) }}%">
            </div>
        </div>

        <div class="mt-4">
            <x-filament::button tag="a" href="/admin/study-sessions" size="xs" color="gray" class="w-full">
                Log Sesi
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>