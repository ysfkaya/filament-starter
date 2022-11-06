<?php

namespace App\Filament\Resources\CMS\PostCategoryResource\Pages;

use App\Filament\Resources\CMS\PostCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostCategories extends ListRecords
{
    protected static string $resource = PostCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
