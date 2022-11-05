<?php

namespace App\Filament\Resources\Management\RoleResource\Pages;

use App\Filament\Resources\Management\RoleResource;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\ListRoles as BaseListRoles;

class ListRoles extends BaseListRoles
{
    protected static string $resource = RoleResource::class;
}
