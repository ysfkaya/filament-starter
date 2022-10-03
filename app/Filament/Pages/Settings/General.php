<?php

namespace App\Filament\Pages\Settings;

use App\Overrides\Filament\Forms\Components\SettingsMediaLibraryFileUpload;
use App\Overrides\Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\TextInput;

class General extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-sun';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $slug = 'settings/general';

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

            SettingsMediaLibraryFileUpload::make('site_logo')->label('Site Logo')->image(),
            SettingsMediaLibraryFileUpload::make('site_favicon')->label('Site Favicon')->image(),
        ];
    }
}
