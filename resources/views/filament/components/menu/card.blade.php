@props(['tree', 'maxDepth', 'addItemAction', 'selectedGroup'])

<div
    {{ $attributes->class([
        'p-6 bg-white rounded-xl border border-gray-300 filament-forms-card-component overflow-auto w-full min-h-[400px]',
        'dark:border-gray-600 dark:bg-gray-800' => config('forms.dark_mode'),
        'flex justify-center items-center' => $tree->isEmpty(),
    ]) }}>
    <div class="p-2 text-sm text-center text-gray-400 font-bold">
        @if ($tree->isEmpty())
            <div class="flex flex-col justify-center items-center gap-4">
                <span>
                    You have not any menu items for the <span class="font-bold text-white">{{ str($selectedGroup)->headline() }}</span>. You can add an item using the below button.
                </span>

                <x-filament::pages.actions :actions="[$addItemAction]" class="shrink-0" />
            </div>
        @else
            <span>
                Use the movement actions where is located in a menu item right side to reorder them.
                You can reveal more options by pressing edit button.
            </span>
        @endif
    </div>

    <div class="flex flex-col items-start py-4">
        <x-menu::tree :tree="$tree" :max-depth="$maxDepth" />
    </div>
</div>
