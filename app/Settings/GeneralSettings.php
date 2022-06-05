<?php

namespace App\Settings;

use App\DTO\Image;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_title;

    public string $site_description;

    public ?string $site_keywords;

    public ?string $site_copyright;

    public ?Image $site_logo;

    public ?Image $site_favicon;

    public static function group(): string
    {
        return 'general';
    }
}
