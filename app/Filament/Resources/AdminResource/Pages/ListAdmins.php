<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getTableQuery(): Builder
    {
        // @phpstan-ignore-next-line
        return parent::getTableQuery()->withoutSupers();
    }
}
