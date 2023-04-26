<?php
 
namespace App\Filament\Widgets;
 
use Closure;
use Filament\Tables;
use App\Models\Order;
use App\Models\Modell;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
 
class ListModels extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = "Model List";

    protected function getTableQuery(): Builder
    {
        return Modell::query();
    }
 
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('brand.name')
            ->label('Brand Name')
            ->sortable(),
            Tables\Columns\TextColumn::make('name')
            ->label('Model Name')

            ->sortable(),
            Tables\Columns\TextColumn::make('code')
            ->searchable()
            ->label('Model Code'),
        ];
    }
}