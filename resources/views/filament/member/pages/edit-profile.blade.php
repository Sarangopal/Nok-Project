<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex gap-3">
            <x-filament::button type="submit" color="primary">Save Changes</x-filament::button>
            <x-filament::button tag="a" :href="route('filament.member.pages.member-dashboard')" color="gray">Cancel</x-filament::button>
        </div>
    </form>
</x-filament-panels::page>




