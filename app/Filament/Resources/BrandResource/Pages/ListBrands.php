<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Models\Brand;
use Filament\Pages\Actions;
use App\Filament\Widgets\ListModels;
use App\Filament\Resources\BrandResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount(['modells'])->get();
    }
 
    protected function getFooterWidgets(): array
    {
        return [
            ListModels::class,
        ];
    }
}
