<?php

namespace App\Filament\Resources\CMS\PostResource\Pages;

use App\Filament\Resources\CMS\PostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(fn ($livewire) => $livewire->getRecord()->url.'?preview=1', true),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Actions\Action::make('saveAsDraft')
               ->label('Save as Draft')
               ->action(function () {
                   $this->save();

                   $this->getRecord()->update([
                       'published_at' => null,
                   ]);
               })
               ->color('warning'),
            $this->getCancelFormAction(),
        ];
    }
}
