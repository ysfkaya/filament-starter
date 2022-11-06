<?php

namespace App\Services;

use App\Models\Page;
use Artesaos\SEOTools\Contracts\SEOFriendly;
use Artesaos\SEOTools\Contracts\SEOTools;
use Illuminate\Support\Str;

class PageSEO implements SEOFriendly
{
    public function __construct(
        public Page $page,
    ) {
    }

    /**
     * Performs SEO settings.
     *
     * @param  SEOTools  $seo
     */
    public function loadSEO(SEOTools $seo)
    {
        $description = data_get($this->page, 'seo.description');

        $title = data_get($this->page, 'seo.title') ?: $this->page->title;

        if ($title && $title !== $seo->metatags()->getDefaultTitle()) {
            $seo->setTitle($title);
        }

        $seo->setDescription(Str::squish(trim($description)))
            ->setCanonical($this->page->url);

        $url = $this->page->getFirstMediaUrl('og_image');

        if ($url) {
            $seo->addImages($url);
        }
    }
}
