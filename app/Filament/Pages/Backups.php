<?php

namespace App\Filament\Pages;

use App\Models\Admin;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Facades\Filament;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;

class Backups extends BaseBackups
{
    use HasPageShield;

    protected static ?int $navigationSort = 1;

    public static function canView(): bool
    {
        /** @var Admin $user */
        $user = Filament::auth()->user();

        return $user->isSuper();
    }

    protected function getActions(): array
    {
        return [];
    }

    protected static function getNavigationGroup(): ?string
    {
        return 'System';
    }
}
