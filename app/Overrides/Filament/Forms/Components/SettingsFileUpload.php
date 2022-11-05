<?php

namespace App\Overrides\Filament\Forms\Components;

use App\DTO\Image;
use App\Overrides\Filament\Pages\SettingsPage;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;

final class SettingsFileUpload extends FileUpload
{
    protected bool | \Closure $shouldPreserveFilenames = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (BaseFileUpload $component, string | array | null $state): void {
            if (blank($state)) {
                $component->state([]);

                return;
            }

            $state = [json_decode($state, true)];

            $files = collect($state)
                ->map(static function (array $file) {
                    return Image::from($file);
                })
                ->filter(static fn (Image $file) => blank($file) || $component->getDisk()->exists($file->path))
                ->mapWithKeys(static fn (Image $file): array => [((string) Str::uuid()) => $file->path])
                ->all();

            $component->state($files);
        });

        $this->beforeStateDehydrated(static function (SettingsFileUpload $component, $state): void {
            if (blank($state)) {
                $component->removeOldFile();

                $component->state(null);

                return;
            }

            $component->saveUploadedFiles();
        });

        $this->dehydrated(false);

        $this->saveUploadedFileUsing(static function (SettingsFileUpload $component, TemporaryUploadedFile $file): ?string {
            $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

            if (! $file->exists()) {
                return null;
            }

            $path = $file->{$storeMethod}(
                $component->getDirectory(),
                $component->getUploadedFileNameForStorage($file),
                $component->getDiskName(),
            );

            $key = $component->settingKey();

            $component->removeOldFile();

            setting([
                $key => Image::fromTemporaryFile(
                    $component->getDiskName(),
                    $file,
                    $path,
                )->toJson(),
            ])->save();

            return $path;
        });

        $this->getUploadedFileNameForStorageUsing(static function (BaseFileUpload $component, TemporaryUploadedFile $file) {
            return $file->getClientOriginalName();
        });
    }

    public function settingKey()
    {
        /** @var SettingsPage $livewire */
        $livewire = $this->getLivewire();

        return "{$livewire->group()}.{$this->getName()}";
    }

    public function removeOldFile()
    {
        $key = $this->settingKey();

        // Delete the old file if it exists.
        if (filled($payload = setting($key))) {
            $target = json_decode($payload, true);

            $path = data_get($target, 'path');
            $disk = data_get($target, 'disk');

            $storage = Storage::disk($disk);

            if (filled($path) && filled($disk) && $storage->exists($path)) {
                $storage->delete($path);

                setting()->forget($key);

                setting()->save();
            }
        }
    }
}
