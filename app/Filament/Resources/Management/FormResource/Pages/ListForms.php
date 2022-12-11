<?php

namespace App\Filament\Resources\Management\FormResource\Pages;

use App\Filament\Resources\Management\FormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForms extends ListRecords
{
    protected static string $resource = FormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
