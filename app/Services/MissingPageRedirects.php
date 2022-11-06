<?php

namespace App\Services;

use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;

class MissingPageRedirects implements Redirector
{
    public function getRedirectsFor(Request $request): array
    {
        $urls = collect(setting('redirects.urls', []));

        return collect($urls)
            ->filter(fn ($url) => isset($url['enabled'], $url['source'], $url['destination']))
            ->filter(fn ($url) => $url['enabled'])
            ->mapWithKeys(fn ($url) => [
                $url['source'] => [$url['destination'], $url['status_code'] ?? 302],
            ])->toArray();
    }
}
