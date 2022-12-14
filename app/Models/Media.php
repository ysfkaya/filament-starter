<?php

namespace App\Models;

use App\Traits\QueryCacheable;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use QueryCacheable;
}
