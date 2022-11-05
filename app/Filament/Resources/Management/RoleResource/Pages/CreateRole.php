<?php

namespace App\Filament\Resources\Management\RoleResource\Pages;

use App\Filament\Resources\Management\RoleResource;
use BezhanSalleh\FilamentShield\Resources\RoleResource\Pages\CreateRole as BaseCreateRole;

class CreateRole extends BaseCreateRole
{
    protected static string $resource = RoleResource::class;
}
