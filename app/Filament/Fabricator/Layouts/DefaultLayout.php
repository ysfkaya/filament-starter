<?php

namespace App\Filament\Fabricator\Layouts;

use Z3d0X\FilamentFabricator\Layouts\Layout;

class DefaultLayout extends Layout
{
    protected static ?string $name = 'default';

    public static function getComponent(): string
    {
        if (isset(static::$component)) {
            return static::$component;
        }

        return 'layouts.' . static::getName();
    }
}
