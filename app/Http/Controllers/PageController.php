<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PageSEO;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use SEOTools;

    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ?string $slug = null)
    {
        $slug ??= '/';

        $page = Page::whereSlug($slug);

        if (! $request->boolean('preview') || ! auth('admin')->check()) {
            $page->published();
        }

        /** @var Page $page */
        $page = $page->firstOrFail();

        $this->loadSEO(new PageSEO($page));

        return $page->render();
    }
}
