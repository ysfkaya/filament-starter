<?php

namespace App\Filament\Resources\CMS\PostResource\Pages;

use App\Filament\Pages\Settings\Post;
use App\Filament\Resources\CMS\PostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('view_settings')
                ->label('Settings')
                ->icon('heroicon-o-cog')
                ->url(route(Post::getRouteName())),
        ];
    }
}
