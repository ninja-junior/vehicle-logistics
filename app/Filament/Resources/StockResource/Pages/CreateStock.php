<?php

namespace App\Filament\Resources\StockResource\Pages;

use Filament\Pages\Actions;
use Filament\Forms\Components\Card;
use App\Filament\Resources\StockResource;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateStock extends CreateRecord
{
    use HasWizard;
    protected static string $resource = StockResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Vehicle Information')
                ->schema([
                    Card::make(StockResource::getFormSchema())->columns(),
                ]),

            Step::make('Stock Information')
                ->schema([
                    Card::make(StockResource::getFormSchema('stock')),
                ]),
        ];
    }
}
