<x-menu::accordion-group group="menu-items" class="w-full">
    <x-filament::form wire:submit.prevent="addItem">
        <x-menu::accordion title="Custom Link" collapsed class="gap-8 w-full">
            {{ $this->customLinkForm }}

            <x-slot:actions>
                <x-filament::form.actions :actions="[$this->getCachedFormAction('addCustomLink')]" :full-width="$this->hasFullWidthFormActions()" />
            </x-slot:actions>
        </x-menu::accordion>
    </x-filament::form>
</x-menu::accordion-group>
