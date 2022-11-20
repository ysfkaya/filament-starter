@props([
    'title' => null,
    'dir' => 'ltr',
])
@props([
    'dir' => 'ltr',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $dir }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <style>
        [x-cloak=""],
        [x-cloak="x-cloak"],
        [x-cloak="1"] {
            display: none !important;
        }
    </style>

    @vite('resources/css/app.css')

    @stack('styles')

    @setting('general.site_head')
</head>

<body>
    @setting('general.site_body')

    {{ $slot }}

    @vite('resources/js/app.js')

    @stack('scripts')

    @setting('general.site_footer')
</body>

</html>
