<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Spatie\LaravelData\Data;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Image extends Data implements Htmlable, Jsonable
{
    public function __construct(
        public ?string $id = null,
        public ?string $uuid = null,
        public ?string $name = null,
        public ?string $file_name = null,
        public ?string $mime_type = null,
        public ?string $path = null,
        public ?string $disk = null,
        public ?int $size = null,
    ) {
    }

    public static function fromMedia(Media $media)
    {
        return new self(
            id: $media->id,
            uuid: $media->uuid,
            name: $media->name,
            file_name: $media->file_name,
            mime_type: $media->mime_type,
            path: Str::after($media->getUrl(), asset('/')),
            disk: $media->disk,
            size: $media->size,
        );
    }

    public static function fromTemporaryFile(string $disk, TemporaryUploadedFile $file, string $path)
    {
        return new self(
            id: null,
            uuid: null,
            name: pathinfo($file->getFilename(), PATHINFO_FILENAME),
            file_name: $file->getClientOriginalName(),
            mime_type: $file->getMimeType(),
            path: $path,
            disk: $disk,
            size: $file->getSize(),
        );
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


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->getMedia()?->toResponse($request) ?? parent::toResponse($request);
    }
}
