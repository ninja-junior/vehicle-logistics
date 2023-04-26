<?php

namespace App\Filament\Resources\ImportLicenseResource\Pages;

use App\Filament\Resources\ImportLicenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportLicense extends EditRecord
{
    protected static string $resource = ImportLicenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
