<?php

namespace App\Http\Controllers;

use App\Enums\PageTemplate;
use App\Enums\SectionType;
use App\Models\Course;
use App\Models\Page;
use App\Services\PageSEO;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;

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

        $page = $page->firstOrFail();

        $this->loadSEO(new PageSEO($page));

        return $page->view();
    }
}
