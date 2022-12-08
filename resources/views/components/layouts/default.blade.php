@props(['page','media' => null])

<x-layouts.app :$page :$media>
    <x-filament-fabricator::page-blocks :blocks="$page->blocks" :$media />
</x-layouts.app>
