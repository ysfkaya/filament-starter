<?php

namespace App\Overrides\Filament\Forms\Components;

use App\DTO\Image;
use App\Models\SettingsProperty;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Pages\SettingsPage;
use Illuminate\Database\Eloquent\Model;
use Livewire\TemporaryUploadedFile;
use Spatie\LaravelSettings\Settings;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SettingsMediaLibraryFileUpload extends SpatieMediaLibraryFileUpload
{
    protected bool | \Closure $shouldPreserveFilenames = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->beforeStateDehydrated(static function (SpatieMediaLibraryFileUpload $component): void {
            $component->saveUploadedFiles();
        });

        $this->deleteUploadedFileUsing(static function (self $component, string $file): void {
            if (! $file) {
                return;
            }

            $mediaClass = config('media-library.media_model', Media::class);

            $mediaClass::findByUuid($file)?->delete();

            $component->saveSetting(Image::empty());
        });

        $this->saveUploadedFileUsing(static function (self $component, TemporaryUploadedFile $file, ?Model $record): string {
            if (! method_exists($record, 'addMediaFromString')) {
                return $file;
            }

            /** @var FileAdder $mediaAdder */
            $mediaAdder = $record->addMediaFromString($file->get());

            $filename = $component->getUploadedFileNameForStorage($file);

            $media = $mediaAdder
                ->usingFileName($filename)
                ->usingName($component->getMediaName($file) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                ->withCustomProperties($component->getCustomProperties())
                ->toMediaCollection($component->getCollection(), $component->getDiskName());

            if ($component->getSettingsProperty() instanceof Image) {
                $component->saveSetting(Image::fromMedia($media));
            }

            return $media->getAttributeValue('uuid');
        });
    }

    public function getRecord(): ?Model
    {
        $settings = $this->getSettings();

        $group = $settings::group();

        return SettingsProperty::fetch($group, $this->name);
    }

    /**
     * @return Settings
     */
    public function getSettings()
    {
        /** @var SettingsPage $component */
        $component = $this->container->getLivewire();

        $settingsClass = $component::getSettings();

        return app($settingsClass);
    }

    public function getSettingsProperty()
    {
        $settings = $this->getSettings();

        return $settings->{$this->name};
    }

    public function saveSetting($value)
    {
        $settings = $this->getSettings();

        $settings->fill([
            $this->name => $value,
        ])->save();
    }
}
