@props([
    'dir' => 'ltr',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $dir }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{ $head ?? '' }}

    {!! SEO::generate() !!}

    <style>
        [x-cloak=""],
        [x-cloak="x-cloak"],
        [x-cloak="1"] {
            display: none !important;
        }
    </style>

    @vite('resources/css/app.css')

    {{ $styles ?? '' }}

    @setting('general.site_head')
</head>

<body>
    @setting('general.site_body')

    {{ $slot }}

    @vite('resources/js/app.js')

    {{ $scripts ?? '' }}

    <script>
        const oldFetch = window.fetch

        window.fetch = async (url, options) => {
            let response = await oldFetch(url, options)
            const isLivewireRequest = options && options.headers && options.headers['X-Livewire'] === true;
            if (isLivewireRequest && response.redirected) {
                setTimeout(() => {
                    window.location.href = response.url;
                }, 50);
            }
            return response;
        }
    </script>

    @setting('general.site_footer')
</body>

</html>
