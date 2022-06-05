<?php

namespace App\Overrides\Filament\Pages;

use App\DTO\Image;
use Filament\Pages\SettingsPage as FilamentSettingsPage;

class SettingsPage extends FilamentSettingsPage
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return collect($data)->map(function ($value) {
            if ($value instanceof Image) {
                $value = $this->mutateImageDTO($value);
            }

            return $value;
        })->all();
    }

    protected function mutateImageDTO(Image $image): ?string
    {
        return $image->uuid;
    }
}
