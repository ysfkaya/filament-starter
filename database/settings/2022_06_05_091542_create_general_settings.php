<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('site_title', 'Site Title');
            $blueprint->add('site_description', 'Site Description');
            $blueprint->add('site_keywords', 'site, keywords');
            $blueprint->add('site_copyright', '@copyright {date} Site Name');

            $blueprint->add('site_logo');
            $blueprint->add('site_favicon');
        });
    }
}
