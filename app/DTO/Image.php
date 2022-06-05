<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Image extends DataTransferObject implements Responsable, Htmlable
{
    public ?string $id;

    public ?string $uuid;

    public ?string $name;

    public ?string $file_name;

    public ?string $mime_type;

    public ?string $url;

    public ?string $path;

    public ?int $size;

    public static function empty()
    {
        return new self();
    }

    public static function fromMedia(Media $media)
    {
        return new self([
            'id' => $media->id,
            'uuid' => $media->uuid,
            'name' =>  $media->name,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'url' => $media->getFullUrl(),
            'path' => Str::after($media->getUrl(), asset('/')),
            'size' => $media->size,
        ]);
    }

    public function getMedia(): ?Media
    {
        if (! $this->uuid) {
            return null;
        }

        /** @var Media|null $media */
        $media = Media::findByUuid($this->uuid);

        return $media;
    }

    public function toHtml()
    {
        return $this->getMedia()?->toHtml() ?? '';
    }

    public function toResponse($request)
    {
        return $this->getMedia()?->toResponse($request) ?? '';
    }
}
