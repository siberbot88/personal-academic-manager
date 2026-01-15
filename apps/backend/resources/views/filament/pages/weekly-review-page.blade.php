<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Block 1: Consistency --}}
        <div class="p-6 bg-white rounded-xl shadow border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-fire" class="w-5 h-5 text-primary-500" />
                Konsistensi
            </h3>
            <div
                class="text-3xl font-black {{ $consistency['achieved'] ? 'text-green-600' : 'text-gray-700 dark:text-gray-200' }}">
                {{ $consistency['count'] }} <span class="text-base font-medium text-gray-500">/
                    {{ $consistency['target'] }} sesi</span>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                Streak: <strong>{{ $consistency['streak'] }} hari</strong>
            </div>
            <div
                class="mt-4 p-3 rounded-lg text-sm {{ $consistency['achieved'] ? 'bg-green-50 text-green-700' : 'bg-orange-50 text-orange-700' }}">
                @if($consistency['achieved'])
                    Target terpenuhi. Pertahankan! <x-filament::icon icon="heroicon-m-rocket-launch"
                        class="w-4 h-4 inline" />
                @else
                    Belum mencapai target. Stop cari alasan. <x-filament::icon icon="heroicon-m-x-circle"
                        class="w-4 h-4 inline" />
                @endif
            </div>
        </div>

        {{-- Block 2: Start Early --}}
        <div class="p-6 bg-white rounded-xl shadow border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-bolt" class="w-5 h-5 text-blue-500" />
                Anti-Menunda (H-7)
            </h3>
            <div class="text-3xl font-black text-blue-600">
                {{ $startEarly['percentage'] }}%
            </div>
            <div class="mt-1 text-xs text-gray-500">Task disentuh sebelum H-7</div>

            @if(count($startEarly['high_risk_tasks']) > 0)
                <div class="mt-4">
                    <div class="text-xs font-bold text-red-600 uppercase mb-1">High Risk (Due Soon & Untouched):</div>
                    <ul class="space-y-1">
                        @foreach($startEarly['high_risk_tasks'] as $task)
                            <li class="text-sm truncate flex items-center gap-1">
                                <x-filament::icon icon="heroicon-m-exclamation-triangle" class="w-3 h-3" />
                                {{ $task->title }} ({{ $task->due_date->format('d M') }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="mt-4 text-sm text-green-600 flex items-center gap-1">
                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4" />
                    Aman! Tidak ada task urgent yang belum disentuh.
                </div>
            @endif
        </div>

        {{-- Block 3: Execution --}}
        <div class="p-6 bg-white rounded-xl shadow border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-check-circle" class="w-5 h-5 text-green-500" />
                Eksekusi
            </h3>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Selesai minggu ini:</span>
                <span class="text-xl font-bold">{{ $execution['done_this_week'] }}</span>
            </div>
            <div class="flex justify-between items-center text-red-600">
                <span>Overdue:</span>
                <span class="text-xl font-bold">{{ $execution['overdue'] }}</span>
            </div>
        </div>
    </div>

    {{-- Block 4: Plan --}}
    <form wire:submit="submitPlan" class="space-y-4">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit">
                Simpan Komitmen
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>