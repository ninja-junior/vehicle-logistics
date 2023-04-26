<?php

namespace App\Filament\Resources\CustomsResource\Pages;

use App\Filament\Resources\CustomsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustoms extends CreateRecord
{
    protected static string $resource = CustomsResource::class;
}
