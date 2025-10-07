<x-filament::section :aside="true" :heading="__('filament-breezy::default.profile.personal_info.heading')" :description="__('filament-breezy::default.profile.personal_info.subheading')">
    <form wire:submit.prevent="submit" class="space-y-6">

        {{ $this->form }}

        <div class="text-right">
            <x-filament::button type="submit" class="align-right">
                Update
            </x-filament::button>
        </div>
    </form>
</x-filament::section>
