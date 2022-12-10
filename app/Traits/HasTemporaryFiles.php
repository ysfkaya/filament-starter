<?php

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\FileUploadConfiguration;
use Livewire\TemporaryUploadedFile;

trait HasTemporaryFiles
{
    protected function uploadTemporaryImages($data, callable $uploadCallback, $valueFrom = null, $parentPostFix = null)
    {
        $imageIndexes = $this->resolveImageIndexes(
            data:$data,
            valueFrom:$valueFrom,
            parentPostFix:$parentPostFix
        );

        foreach ($imageIndexes as $index) {
            $image = data_get($data, $index);

            if (! $image) {
                continue;
            }

            /** @var TemporaryUploadedFile $storage */
            $storage = TemporaryUploadedFile::unserializeFromLivewireRequest($image);

            if (! $storage->exists()) {
                continue;
            }

            $media = $uploadCallback($storage, $index);

            $uuid = $media?->getAttributeValue('uuid');

            if (! $uuid && $media) {
                $media->forceFill([
                    'uuid' => (string) Str::uuid(),
                ])->save();

                $uuid = serializeMediaUuid($media->getAttributeValue('uuid'));
            }

            data_set($data, $index, $uuid);

            $storage->delete();
        }

        return $data;
    }

    protected function resolveImageIndexes(array $data, $indexes = [], $parent = null, $valueFrom = null, $parentPostFix = null)
    {
        foreach ($data as $index => $value) {
            if (is_string($value)) {
                if (TemporaryUploadedFile::canUnserialize($value)) {
                    $indexes[] = trim($parent.'.'.$index, '.');
                }
            }

            if ($valueFrom && isset($value[$valueFrom])) {
                $value = $value[$valueFrom];
            }

            if (is_array($value) || $value instanceof Arrayable) {
                $value = $value instanceof Arrayable ? $value->toArray() : $value;

                $indexes = $this->resolveImageIndexes($value, $indexes, trim($parent.'.'.$index, '.').$parentPostFix);
            }
        }

        return $indexes;
    }

    /**
     * @return string|TemporaryUploadedFile|array
     */
    protected function uploadFile(string $path, bool $serialize = true, bool $getContents = false)
    {
        if (str_starts_with($path, 'http')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_REFERER, 'http://www.xcontest.org');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $contents = curl_exec($ch);
            curl_close($ch);
        } else {
            $contents = file_get_contents($path);
        }

        if ($getContents) {
            return $contents;
        }

        $name = pathinfo($path, PATHINFO_BASENAME);

        $tmpFile = tempnam(sys_get_temp_dir(), 'livewire-temporary-file');

        file_put_contents($tmpFile, $contents);

        $uploadedFile = new UploadedFile(
            $tmpFile,
            $name,
            mime_content_type($tmpFile),
            filesize($tmpFile),
            app()->runningUnitTests()
        );

        $filename = TemporaryUploadedFile::generateHashNameWithOriginalNameEmbedded($uploadedFile);

        $storedFilename = $uploadedFile->storeAs(
            '/'.($dir = FileUploadConfiguration::path()),
            $filename,
            FileUploadConfiguration::disk()
        );

        $path = str_replace($dir.'/', '', $storedFilename);

        $file = TemporaryUploadedFile::createFromLivewire($path);

        if (is_file($tmpFile)) {
            unlink($tmpFile);
        }

        if ($serialize) {
            return $file->serializeForLivewireResponse();
        }

        return $file;
    }
}
