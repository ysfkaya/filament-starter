<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use App\Models\Admin;
use Filament\Resources\Pages\EditRecord;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $getUser = Admin::where('email', $data['email'])->first();

        if ($getUser) {
            if (empty($data['password'])) {
                $data['password'] = $getUser->password;
            }
        }

        return $data;
    }
}
