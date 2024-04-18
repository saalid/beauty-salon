<?php

namespace App\Filament\Resources\UserBoughtLicenseResource\Pages;

use App\Filament\Resources\UserBoughtLicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUserBoughtLicenses extends ManageRecords
{
    protected static string $resource = UserBoughtLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
