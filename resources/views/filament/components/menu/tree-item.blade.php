@props(['item', 'moveUp', 'moveDown', 'indent', 'dedent', 'path', 'child'])

@php
$id ??= Str::uuid();
@endphp

<li wire:key="menu-tree-item-{{ $id }}" class="mt-4">
    <header
        {{ $attributes->class([
            'flex items-center h-14 overflow-hidden bg-white border border-gray-300 shadow-sm rounded-sm relative',
            'dark:bg-gray-800 dark:border-gray-600' => config('forms.dark_mode'),
        ]) }}>

        <div class="py-4 pl-6 text-base text-center text-gray-400">
            {{ $item['title'] }}
        </div>

        <div class="flex-1"></div>

        <ul @class([
            'flex divide-x',
            'dark:divide-gray-700' => config('forms.dark_mode'),
        ])>

            @if ($child)
                <li>
                    <span @class([
                        'flex items-center justify-center mr-5 h-full flex-none text-gray-400 transition hover:text-gray-300 text-xs',
                        'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                    ])>sub item</span>
                </li>
            @endif

            @if ($moveUp)
                <li>
                    <button type="button" wire:click="moveItemUp('{{ $path }}')" @class([
                        'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                        'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                    ])
                        x-tooltip.raw.duration.0="Move Up">
                        <x-heroicon-o-arrow-up class="w-4 h-4 text-gray-500 hover:text-gray-900" />
                    </button>
                </li>
            @endif

            @if ($moveDown)
                <li>
                    <button type="button" wire:click="moveItemDown('{{ $path }}')" @class([
                        'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                        'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                    ])
                        x-tooltip.raw.duration.0="Move Down">
                        <x-heroicon-o-arrow-down class="w-4 h-4 text-gray-500 hover:text-gray-900" />
                    </button>
                </li>
            @endif

            @if ($indent)
                <li>
                    <button type="button" wire:click="indentItem('{{ $path }}')" @class([
                        'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                        'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                    ])
                        x-tooltip.raw.duration.0="Indent">
                        <x-heroicon-o-arrow-right class="w-4 h-4 text-gray-500 hover:text-gray-900" />
                    </button>
                </li>
            @endif

            @if ($dedent)
                <li>
                    <button type="button" wire:click="dedentItem('{{ $path }}')" @class([
                        'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                        'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                    ])
                        x-tooltip.raw.duration.0="Dedent">
                        <x-heroicon-o-arrow-left class="w-4 h-4 text-gray-500 hover:text-gray-900" />
                    </button>
                </li>
            @endif

            <li>
                <button type="button" wire:click="editItem('{{ $path }}')" @class([
                    'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                    'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                ])
                    x-tooltip.raw.duration.0="Edit">
                    <x-heroicon-s-pencil-alt class="w-4 h-4 text-gray-500 hover:text-gray-900" />
                </button>
            </li>

            <li>
                <button wire:click="removeItem('{{ $path }}')" type="button" @class([
                    'flex items-center justify-center flex-none w-10 h-10 text-danger-600 transition hover:text-danger-500',
                    'dark:text-danger-500 dark:hover:text-danger-400' => config(
                        'forms.dark_mode'
                    ),
                ])
                    x-tooltip.raw.duration.0="Remove">
                    <span class="sr-only">
                        {{ __('forms::components.repeater.buttons.delete_item.label') }}
                    </span>

                    <x-heroicon-s-trash class="w-4 h-4" />
                </button>
            </li>
        </ul>
    </header>

    {{ $subTree ?? null }}
</li>
