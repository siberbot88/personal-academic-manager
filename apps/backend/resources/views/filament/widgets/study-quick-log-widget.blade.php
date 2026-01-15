<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <h2 class="text-lg font-bold">Catat Sesi Belajar</h2>

            {{-- Stats Mini --}}
            <div class="flex gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1">
                    <x-filament::icon icon="heroicon-m-fire" class="w-4 h-4 text-primary-500" />
                    Streak: <strong>{{ $streak }} hari</strong>
                </span>
                <span class="flex items-center gap-1">
                    <x-filament::icon icon="heroicon-m-calendar" class="w-4 h-4 text-gray-500" />
                    Hari ini: <strong>{{ $todaysCount }} sesi</strong>
                </span>
            </div>

            {{-- Mode Selection --}}
            <div class="flex gap-2">
                @foreach (['study' => 'Belajar', 'review' => 'Review', 'writing' => 'Nulis'] as $key => $label)
                    <button wire:click="setMode('{{ $key }}')"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors
                                {{ $mode === $key ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Form Context --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{ $this->form }}
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-3 gap-4 mt-4">
                <button wire:click="logSession(25)"
                    class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg border border-blue-200 transition">
                    <span class="text-2xl font-bold">25m</span>
                    <span class="text-xs">Pomodoro</span>
                </button>
                <button wire:click="logSession(50)"
                    class="flex flex-col items-center justify-center p-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg border border-indigo-200 transition">
                    <span class="text-2xl font-bold">50m</span>
                    <span class="text-xs">Deep Work</span>
                </button>
                <button wire:click="logSession(120)"
                    class="flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg border border-purple-200 transition">
                    <span class="text-2xl font-bold">120m</span>
                    <span class="text-xs">Marathon</span>
                </button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>