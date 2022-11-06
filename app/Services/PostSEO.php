<?php

namespace App\Services;

use App\Models\Post;
use Artesaos\SEOTools\Contracts\SEOFriendly;
use Artesaos\SEOTools\Contracts\SEOTools;

class PostSEO implements SEOFriendly
{
    public function __construct(
        public Post $post,
    ) {
    }

    /**
     * Performs SEO settings.
     *
     * @param  SEOTools  $seo
     */
    public function loadSEO(SEOTools $seo)
    {
        $seo->setTitle(data_get($this->post, 'seo.title') ?: data_get($this->post, 'title'))
        ->setDescription(data_get($this->post, 'seo.description') ?: $this->post->summary(20))
        ->setCanonical($this->post->url);

        $image = $this->post->getFirstMediaUrl();

        if ($image) {
            $seo->addImages($image);
        }
    }
}
