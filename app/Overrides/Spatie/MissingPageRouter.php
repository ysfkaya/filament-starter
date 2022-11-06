<?php

namespace App\Overrides\Spatie;

use Exception;
use Illuminate\Support\Str;
use Spatie\MissingPageRedirector\Events\RedirectNotFound;
use Spatie\MissingPageRedirector\Events\RouteWasHit;
use Spatie\MissingPageRedirector\MissingPageRouter as BaseMissingPageRouter;
use Symfony\Component\HttpFoundation\Request;

class MissingPageRouter extends BaseMissingPageRouter
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getRedirectFor(Request $request)
    {
        $redirects = $this->redirector->getRedirectsFor($request);

        collect($redirects)->each(function ($redirects, $missingUrl) {
            if (Str::contains($missingUrl, '*')) {
                $missingUrl = str_replace('*', '{wildcard}', $missingUrl);
            }

            $this->router->get($missingUrl, function () use ($redirects, $missingUrl) {
                $redirectUrl = $this->determineRedirectUrl($redirects);
                $statusCode = $this->determineRedirectStatusCode($redirects);

                event(new RouteWasHit($redirectUrl, $missingUrl, $statusCode));

                $redirector = redirect();

                if ($redirector instanceof \Livewire\Redirector) {
                    // @phpstan-ignore-next-line
                    return invade($redirector)->createRedirect($redirectUrl, $statusCode, []);
                }

                return $redirector->to(
                    $redirectUrl,
                    $statusCode
                );
            })->where('wildcard', '.*');
        });

        try {
            return $this->router->dispatch($request);
        } catch (Exception $e) {
            event(new RedirectNotFound($request));

            return null;
        }
    }
}
