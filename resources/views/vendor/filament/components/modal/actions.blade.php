<x-filament-support::modal.actions
    :attributes="\Filament\Support\prepare_inherited_attributes($attributes)"
    :align="config('filament.layout.forms.actions.alignment')"
    :dark-mode="config('filament.dark_mode')"
>
    {{ $slot }}
</x-filament-support::modal.actions>
