@php
    $alignment = 'top-right';
@endphp

@if (
    filament()->hasDarkMode() &&
    (! filament()->hasDarkModeForced())
)
    <div @class([
        'fixed w-full flex p-4 z-40 auth-theme-switcher',
        'top-0' => str_contains($alignment, 'top'),
        'bottom-0' => str_contains($alignment, 'bottom'),
        'justify-start' => str_contains($alignment, 'left'),
        'justify-end' => str_contains($alignment, 'right'),
        'justify-center' => str_contains($alignment, 'center'),
    ])>
        <div class="rounded-lg bg-gray-50 dark:bg-gray-950">
            <div
                x-data="{
                    theme: null,

                    init: function () {
                        this.theme = localStorage.getItem('theme') || @js(filament()->getDefaultThemeMode()->value)

                        $dispatch('theme-changed', theme)

                        $watch('theme', (theme) => {
                            $dispatch('theme-changed', theme)
                        })
                    },
                }"
                class="fi-theme-switcher grid grid-flow-col gap-x-1"
            >
                @include('filament.switcher.button', ['icon' => 'heroicon-m-sun', 'theme' => 'light'])
                @include('filament.switcher.button', ['icon' => 'heroicon-m-moon', 'theme' => 'dark'])
                @include('filament.switcher.button', ['icon' => 'heroicon-m-computer-desktop', 'theme' => 'system'])
            </div>
        </div>
    </div>
@endif
