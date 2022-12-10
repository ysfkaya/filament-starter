<?php

namespace App\Filament\Forms;

use Filament\Forms;
use Filament\Forms\Components\Concerns\EntanglesStateWithSingularRelationship;

class Button extends Forms\Components\Field
{
    use EntanglesStateWithSingularRelationship;

    protected string $view = 'forms::components.group';

    public static function make(string $name = 'button'): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }

    protected function setUp(): void
    {
        $this->schema([
            Forms\Components\Section::make('Buton')
                ->schema([
                    Forms\Components\TextInput::make('text')
                        ->label('Metin')
                        ->required(),

                    Forms\Components\TextInput::make('action')
                        ->label('Aksiyon')
                        ->default('#')
                        ->required(),
                ]),
        ]);
    }
}
