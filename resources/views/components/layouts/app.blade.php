@props([
    'page' => null,
])

<x-filament-fabricator::layouts.base>
    <x-slot:head>
        @stack('head')
    </x-slot:head>

    <x-slot:styles>
        @livewireStyles

        {{ $styles ?? '' }}

        @stack('styles')
    </x-slot:styles>

    <x-layouts.app.header :$page />

    <main @class([optional($page)->class])>
        {{ $slot }}
    </main>

    <x-layouts.app.footer :$page />

    <x-slot:scripts>
        @livewireScripts

        {{ $scripts ?? '' }}

        @stack('scripts')
    </x-slot:scripts>
</x-filament-fabricator::layouts.base>
