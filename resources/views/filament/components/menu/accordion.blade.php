@props(['title', 'id' => null, 'collapsed' => false])

@aware(['group'])

@php
$id ??= Str::uuid();
@endphp

<li x-data="{ isCollapsed: @json($collapsed) }" wire:key="accordion-item-{{ $group }}-{{ $id }}">
    <header
        {{ $attributes->class([
            'flex items-center h-14 overflow-hidden bg-white border border-gray-300 shadow-sm rounded-sm relative',
            'dark:bg-gray-800 dark:border-gray-600' => config('forms.dark_mode'),
        ]) }}>

        <div class="py-4 pl-6 text-base text-center text-gray-400">
            {{ $title }}
        </div>

        <div class="flex-1"></div>

        <ul @class([
            'flex divide-x',
            'dark:divide-gray-700' => config('forms.dark_mode'),
        ])>

            <li>
                <button x-on:click="isCollapsed = !isCollapsed" type="button" @class([
                    'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                    'dark:text-gray-400 dark:hover:text-gray-500' => config('forms.dark_mode'),
                ])>
                    <x-heroicon-s-minus-sm class="w-4 h-4" x-show="! isCollapsed" />

                    <span class="sr-only" x-show="! isCollapsed">
                        {{ __('forms::components.repeater.buttons.collapse_item.label') }}
                    </span>

                    <x-heroicon-s-plus-sm class="w-4 h-4" x-show="isCollapsed" x-cloak />

                    <span class="sr-only" x-show="isCollapsed" x-cloak>
                        {{ __('forms::components.repeater.buttons.expand_item.label') }}
                    </span>
                </button>
            </li>

        </ul>
    </header>

    <div x-show="isCollapsed"
        {{ $attributes->class([
            'bg-white border border-gray-300 shadow-sm rounded-sm relative',
            'dark:bg-gray-800 dark:border-gray-600' => config('forms.dark_mode'),
        ]) }}>

        <div class="p-6 flex flex-col gap-8">
            {{ $slot }}
        </div>

        @isset($actions)
            <div @class([
                'bg-white border border-gray-300 shadow-sm rounded-sm relative p-6',
                'dark:bg-gray-800 dark:border-gray-600' => config('forms.dark_mode'),
            ])>
                {{ $actions }}
            </div>
        @endisset
    </div>

    {{ $outer ?? null }}
</li>
