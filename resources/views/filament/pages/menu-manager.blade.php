<x-filament::page>
    <div class="flex items-center mb-6">
        <x-filament::header class="mr-5">
            <x-slot:heading>
                Group
            </x-slot:heading>
        </x-filament::header>

        <div class="relative" wire:ignore>
            <select @class([
                'text-gray-900 block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70',
                'dark:bg-gray-700 dark:text-white dark:focus:border-primary-600 dark:border-gray-600' => config(
                    'forms.dark_mode'
                ),
                'border-gray-300',
            ]) wire:model="selectedGroup">
                @foreach ($groups as $value => $label)
                    <option value="{{ $value }}" @selected($value === $selectedGroup)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <x-filament-support::grid  wire:key="menu-items-wrapper">
        <x-menu::card :tree="$tree" :max-depth="$maxDepth" :addItemAction="$this->getCachedAction('addItem')" :selectedGroup="$selectedGroup" />
    </x-filament-support::grid>
</x-filament::page>
