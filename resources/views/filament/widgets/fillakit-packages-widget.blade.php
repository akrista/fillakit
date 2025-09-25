<x-filament-widgets::widget>
    <x-filament::section>
        <div class="mb-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Packages</div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach(($stack ?? []) as $pkg)
                <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-900/40 p-3">
                    <div class="text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-medium">{{ $pkg['label'] }}</span>
                        <span class="text-gray-500">({{ $pkg['package'] }})</span>
                    </div>
                    <span class="text-xs text-gray-500">{{ \Illuminate\Support\Str::startsWith($pkg['version'], ['v','V']) ? $pkg['version'] : 'v'.$pkg['version'] }}</span>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


