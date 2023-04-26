<?php

namespace App\Filament\Resources\CustomsResource\Pages;

use App\Filament\Resources\CustomsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustoms extends EditRecord
{
    protected static string $resource = CustomsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
