<?php

namespace App\Filament\Resources\TransportationResource\Pages;

use App\Filament\Resources\TransportationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransportation extends EditRecord
{
    protected static string $resource = TransportationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
