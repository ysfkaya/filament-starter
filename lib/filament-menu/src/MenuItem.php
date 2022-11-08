<?php

namespace Ysfkaya\Menu;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class MenuItem extends Data
{
    public function __construct(
        public string $title,
        public string $url,
        #[In(['_self', '_blank', '_parent', '_top'])]
        public string $target = '_self',
        public int|string|null $id = null,
        public string|null $group = null,
        public string|null $locale = null,
        public MenuItemCollection|array|null $children = null,
    ) {
        // code...
    }
}
