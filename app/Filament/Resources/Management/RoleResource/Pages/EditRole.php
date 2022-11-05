<?php

namespace App\Filament\Resources\Management\RoleResource\Pages;

use App\Filament\Resources\Management\RoleResource;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\EditRole as BaseEditRole;

class EditRole extends BaseEditRole
{
    protected static string $resource = RoleResource::class;
}
