<?php

namespace App\Generators;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class MediaPathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        return $this->getMediaPathName($media).'/'.$media->collection_name.'/'.$media->getKey();
    }

    /**
     * @param  Media  $media
     * @return string
     */
    protected function getMediaPathName(Media $media)
    {
        if (method_exists($media->model, 'mediaPathName')) {
            return $media->model->mediaPathName();
        }

        $className = $media->model_type;

        return str(class_basename($className))->kebab()->lower()->plural();
    }
}
