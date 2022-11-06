<?php

namespace App\Filament\Resources\CMS\PostCategoryResource\RelationManagers;

use App\Filament\Resources\PostResource;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;

class PostsRelationManager extends RelationManager
{
    protected $queryString = ['tableSortColumn', 'tableSortDirection'];

    protected static string $relationship = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return PostResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return PostResource::table($table)->reorderable('order_column')->defaultSort('order_column');
    }
}
