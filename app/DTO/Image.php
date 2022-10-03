<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Image extends DataTransferObject implements Responsable, Htmlable, Jsonable
{
    public ?string $id;

    public ?string $uuid;

    public ?string $name;

    public ?string $file_name;

    public ?string $mime_type;

    public ?string $path;

    public ?string $disk;

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
            'name' => $media->name,
            'file_name' => $media->file_name,
            'mime_type' => $media->mime_type,
            'path' => Str::after($media->getUrl(), asset('/')),
            'disk' => $media->disk,
            'size' => $media->size,
        ]);
    }

    public static function fromTemporaryFile(string $disk, TemporaryUploadedFile $file, string $path)
    {
        return new self([
            'id' => null,
            'uuid' => null,
            'name' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'path' => $path,
            'disk' => $disk,
            'size' => $file->getSize(),
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

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
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
