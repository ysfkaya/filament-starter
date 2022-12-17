<?php

namespace App\Filament\Pages\Settings;

use App\Overrides\Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components;

class Settings extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $slug = 'management/settings';

    protected static ?string $navigationLabel = 'Settings';

    protected function getFormSchema(): array
    {
        return [
            Components\Tabs::make('Settings')
                ->schema([
                    Components\Tabs\Tab::make('Site')
                        ->schema([
                            Components\TextInput::make('site.title')->label('Title'),
                            Components\TextInput::make('site.description')->label('Description'),

                            Components\TextInput::make('site.copyright')
                            ->label('Copyright')
                            ->placeholder('© {date} site.com'),
                        ]),
                    Components\Tabs\Tab::make('Social Accounts')
                        ->schema([
                            Components\TextInput::make('social.facebook')->label('Facebook')->url(),
                            Components\TextInput::make('social.twitter')->label('Twitter')->url(),
                            Components\TextInput::make('social.instagram')->label('Instagram')->url(),
                            Components\TextInput::make('social.youtube')->label('Youtube')->url(),
                            Components\TextInput::make('social.linkedin')->label('Linkedin')->url(),
                        ]),
                ]),
        ];
    }
}
