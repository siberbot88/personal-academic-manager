<x-filament-panels::page>
    {{ $this->form }}

    @php
        $materials = $this->materials;
        $inboxItems = $this->inboxItems;
    @endphp

    <div class="space-y-6">
        @if ($materials->isEmpty() && $inboxItems->isEmpty())
            <div class="text-center text-gray-500 py-12">
                Tidak ada hasil ditemukan.
            </div>
        @else
            <!-- Materials Section -->
            @if ($materials->isNotEmpty())
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <x-heroicon-o-document-text class="w-5 h-5" />
                        Materials ({{ $materials->count() }})
                    </h2>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach ($materials as $item)
                                <div
                                    class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-700 flex justify-between items-start hover:border-primary-500 transition">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded text-xs font-medium 
                                                        {{ match ($item->type) {
                                'note' => 'bg-yellow-100 text-yellow-800',
                                'link' => 'bg-blue-100 text-blue-800',
                                'file' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800'
                            } }}">
                                                {{ strtoupper($item->type) }}
                                            </span>
                                            <span class="text-gray-500 text-xs">{{ $item->course->name }}</span>
                                        </div>
                                        <h3 class="font-semibold text-lg hover:text-primary-600">
                                            <a
                                                href="{{ \App\Filament\Resources\Materials\MaterialResource::getUrl('edit', ['record' => $item]) }}">
                                                {{ $item->title }}
                                            </a>
                                        </h3>
                                        @if($item->note)
                                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 line-clamp-2">
                                                {{ Str::limit($item->note, 150) }}</p>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-400">
                                            {{ $item->captured_at->diffForHumans() }} • Source: {{ $item->source ?? '-' }}
                                        </div>
                                    </div>
                                    <x-filament::button size="xs" tag="a"
                                        href="{{ \App\Filament\Resources\Materials\MaterialResource::getUrl('edit', ['record' => $item]) }}">
                                        Open
                                    </x-filament::button>
                                </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Inbox Section -->
            @if ($inboxItems->isNotEmpty())
                <div class="space-y-4 pt-4 border-t dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <x-heroicon-o-inbox class="w-5 h-5" />
                        Inbox Items ({{ $inboxItems->count() }})
                    </h2>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach ($inboxItems as $item)
                            <div
                                class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 flex justify-between items-start opacity-75 hover:opacity-100 transition">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-700">INBOX</span>
                                        <span class="text-gray-500 text-xs">{{ $item->course->name }}</span>
                                    </div>
                                    <h3 class="font-medium text-base text-gray-700 dark:text-gray-300">
                                        {{ $item->title }}
                                    </h3>
                                    @if($item->url)
                                        <a href="{{ $item->url }}" target="_blank"
                                            class="text-primary-600 text-xs hover:underline truncate block max-w-md mt-1">
                                            {{ $item->url }}
                                        </a>
                                    @endif
                                </div>
                                <!-- Link to standard inbox view/edit -->
                                <!-- Note: ideally we link to InboxItemResource edit, assuming it exists -->
                                <x-filament::button size="xs" color="gray" tag="a" href="/admin/inbox-items">
                                    Go to Inbox
                                </x-filament::button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-filament-panels::page>