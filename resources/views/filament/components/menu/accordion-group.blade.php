@props(['group'])

<ul {{ $attributes->class(['flex flex-col']) }} wire:key="{{ $group }}">
    {{ $slot }}
</ul>
