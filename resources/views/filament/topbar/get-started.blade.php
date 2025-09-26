<div class="flex items-center">
    <x-filament::button
        tag="a"
        href="{{ config('fillakit.docs_url') }}"
        target="_blank"
        rel="noopener noreferrer"
        color="primary"
        size="md"
        class="inline-flex items-center gap-2"
    >
        <x-filament::icon
            :icon="\Filament\Support\Icons\Heroicon::OutlinedBookOpen"
            class="h-5 w-5"
        />
        <span>Get Started</span>
    </x-filament::button>
</div>


