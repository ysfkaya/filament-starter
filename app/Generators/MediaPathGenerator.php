<?php

namespace App\Generators;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class MediaPathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        return 'media/'.Str::kebab(class_basename($media->model_type)).'/'.$media->model_id.'/'.$media->id;
    }
}
