<?php

namespace App\Filament\Pages\Settings;

use App\Overrides\Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;

class Blog extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $slug = 'blog/settings';

    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = 'Blog Settings';

    public function group(): string
    {
        return 'blog';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('seo.title')->label('Title'),

                    Forms\Components\Textarea::make('seo.description')->label('Description'),
                ]),
        ];
    }
}
