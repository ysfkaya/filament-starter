<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\TemporaryUploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class BlockFileUpload extends SpatieMediaLibraryFileUpload
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadStateFromRelationshipsUsing(static function (BlockFileUpload $component, HasMedia $record): void {
            $statedUuid = $component->unserializedState();

            /** @var Model&HasMedia $record */
            $files = $record->load('media')
                ->getMedia($component->getCollection())
                ->filter(fn (Media $media) => in_array($media->getAttributeValue('uuid'), Arr::wrap($statedUuid), true))
                ->when(
                    ! $component->isMultiple(),
                    fn (Collection $files): Collection => $files->take(1),
                )
                ->mapWithKeys(function (Media $file): array {
                    $uuid = $file->getAttributeValue('uuid');

                    return [$uuid => $uuid];
                })->toArray();

            $component->state(serializeMediaUuid($files));
        });

        $this->dehydrateStateUsing(static function (BlockFileUpload $component, ?array $state): string|array|null {
            $files = array_values($state ?? []);

            $files = serializeMediaUuid($files);

            if ($component->isMultiple()) {
                return $files;
            }

            return $files[0] ?? null;
        });

        $this->dehydrated(true);

        $this->getUploadedFileUrlUsing(static function (BlockFileUpload $component, string $file): ?string {
            if (! $component->getRecord()) {
                return null;
            }

            $file = unserializeMediaUuid($file);

            $mediaClass = config('media-library.media_model', Media::class);

            /** @var ?Media $media */
            $media = $mediaClass::findByUuid($file);

            if ($component->getVisibility() === 'private') {
                try {
                    return $media?->getTemporaryUrl(
                        now()->addMinutes(5),
                    );
                } catch (Throwable $exception) {
                    // This driver does not support creating temporary URLs.
                }
            }

            if ($component->getConversion() && $media->hasGeneratedConversion($component->getConversion())) {
                return $media?->getUrl($component->getConversion());
            }

            return $media?->getUrl();
        });

        $this->deleteUploadedFileUsing(static function (BlockFileUpload $component, string $file): void {
            if (! $file) {
                return;
            }

            $mediaClass = config('media-library.media_model', Media::class);

            $mediaClass::findByUuid($file)?->delete();

            $component->state(null);
        });

        $this->saveUploadedFileUsing(static function (BlockFileUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string {
            if (! method_exists($record, 'addMediaFromString')) {
                return $file;
            }

            if (! $file->exists()) {
                return null;
            }

            /** @var FileAdder $mediaAdder */
            $mediaAdder = $record->addMediaFromString($file->get());

            $filename = $component->getUploadedFileNameForStorage($file);

            $media = $mediaAdder
                ->usingFileName($filename)
                ->usingName($component->getMediaName($file) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                ->storingConversionsOnDisk($component->getConversionsDisk() ?? '')
                ->withCustomProperties($component->getCustomProperties())
                ->withManipulations($component->getManipulations())
                ->withResponsiveImagesIf($component->hasResponsiveImages())
                ->withProperties($component->getProperties())
                ->toMediaCollection($component->getCollection(), $component->getDiskName());

            $state = serializeMediaUuid($media->getAttributeValue('uuid'));

            $component->state($state);

            return $state;
        });

        $this->deleteUploadedFileUsing(static function (BlockFileUpload $component, string $file): void {
            if (! $file) {
                return;
            }

            $file = unserializeMediaUuid($file);

            $mediaClass = config('media-library.media_model', Media::class);

            $mediaClass::findByUuid($file)?->delete();
        });

        $this->reorderUploadedFilesUsing(static function (SpatieMediaLibraryFileUpload $component, array $state): array {
            $uuids = array_filter(array_values($state));
            $uuids = unserializeMediaUuid($uuids);

            $mappedIds = Media::query()->whereIn('uuid', $uuids)->pluck('id', 'uuid')->toArray();

            Media::setNewOrder(array_merge(array_flip($uuids), $mappedIds));

            return $state;
        });
    }

    public function unserializedState(): mixed
    {
        return unserializeMediaUuid($this->getState());
    }
}
