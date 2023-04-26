<?php

namespace App\Filament\Resources\CustomsResource\Pages;

use App\Filament\Resources\CustomsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustoms extends ListRecords
{
    protected static string $resource = CustomsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
