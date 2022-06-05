<?php

namespace App\Models;

use Spatie\LaravelSettings\Models\SettingsProperty as BaseSettingsProperty;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SettingsProperty extends BaseSettingsProperty implements HasMedia
{
    use InteractsWithMedia;

    public static function fetch(string $group, string $name): self|null
    {
        return static::where('group', $group)
            ->where('name', $name)
            ->first();
    }

    public function mediaPathName(): string
    {
        return 'settings';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->singleFile();
    }
}
