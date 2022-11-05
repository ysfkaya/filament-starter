<?php

namespace App\Filament\Resources\Management\UserResource\Pages;

use App\Filament\Resources\Management\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
