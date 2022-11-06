<?php

namespace App\Filament\Pages\Settings;

use App\Overrides\Filament\Forms\Components\SettingsFileUpload;
use App\Overrides\Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\TextInput;

class Settings extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $slug = 'management/settings';

    protected static ?string $navigationLabel = 'Settings';

    public function group(): string
    {
        return 'general';
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('site_title')->label('Site Title')->required(),
            TextInput::make('site_description')->label('Site Description')->required(),

            TextInput::make('site_copyright')->label('Site Copyright'),

            SettingsFileUpload::make('site_logo')->label('Site Logo')->image(),
            SettingsFileUpload::make('site_favicon')->label('Site Favicon')->image(),
        ];
    }
}
