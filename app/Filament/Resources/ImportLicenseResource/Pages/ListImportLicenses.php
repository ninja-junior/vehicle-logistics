<?php

namespace App\Filament\Resources\ImportLicenseResource\Pages;

use App\Filament\Resources\ImportLicenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImportLicenses extends ListRecords
{
    protected static string $resource = ImportLicenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
