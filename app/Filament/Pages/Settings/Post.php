<?php

namespace App\Filament\Pages\Settings;

use App\Overrides\Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;

class Post extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $slug = 'cms/post/settings';

    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = 'Post Settings';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('post.seo.title')->label('Title'),

                    Forms\Components\Textarea::make('post.seo.description')->label('Description'),
                ]),
        ];
    }
}
