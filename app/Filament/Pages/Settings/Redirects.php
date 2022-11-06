<?php

namespace App\Filament\Pages\Settings;

use App\Overrides\Filament\Pages\SettingsPage;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components;

class Redirects extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-s-link';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Redirects';

    protected static ?string $slug = 'system/redirects';

    public function group(): string
    {
        return 'redirects';
    }

    protected function getFormSchema(): array
    {
        return [
            Components\Repeater::make('urls')
                ->columnSpan('full')
                ->collapsible()
                ->schema([
                    Components\Grid::make(2)
                        ->schema([
                            Components\TextInput::make('source')->label('Source')->required()->helperText('The URL you want to redirect from. EX: /old-url. Also supports wildcards. EX: /old-url/*'),
                            Components\TextInput::make('destination')->label('Destination')->required()->helperText('The URL you want to redirect to. EX: /new-url. Also supports wildcards. EX: /new-url/*'),
                        ]),
                    Components\Select::make('status_code')
                        ->label('Status Code')
                        ->required()
                        ->options([
                            301 => '301 - Moved Permanently',
                            302 => '302 - Found',
                            303 => '303 - See Other',
                            307 => '307 - Temporary Redirect',
                            308 => '308 - Permanent Redirect',
                        ])->default(301),
                    Components\Toggle::make('enabled')->label('Enabled')->default(true),
                ]),
        ];
    }
}
