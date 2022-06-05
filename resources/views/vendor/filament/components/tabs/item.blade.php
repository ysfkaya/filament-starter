@props([
    'active' => false,
    'tag' => 'button',
    'type' => 'button',
])

@php
    $buttonClasses = \Illuminate\Support\Arr::toCssClasses([
        'flex items-center h-8 px-5 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-inset filament-tabs-item',
        'hover:text-gray-800 focus:text-primary-600' => ! $active,
        'dark:text-gray-400 dark:hover:text-gray-300 dark:focus:text-primary-400' => (! $active) && config('filament.dark_mode'),
        'text-primary-600 shadow bg-white' => $active,
    ]);
@endphp

@if ($tag === 'button')
    <button
        type="{{ $type }}"
        {{ $attributes->class([$buttonClasses]) }}
    >
        {{ $slot }}
    </button>
@elseif ($tag === 'a')
    <a {{ $attributes->class([$buttonClasses]) }}>
        {{ $slot }}
    </a>
@endif
