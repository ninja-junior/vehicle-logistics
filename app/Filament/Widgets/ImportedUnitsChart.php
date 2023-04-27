<?php

namespace App\Filament\Widgets;

use App\Models\Brand;
use App\Models\Stock;
use Filament\Widgets\BarChartWidget;

class ImportedUnitsChart extends BarChartWidget
{
    protected static ?string $heading = 'Imported & Non-Imported Units by Brand';
    protected static ?int $sort = 1;
    
    protected function getData(): array
    {
        $brands = Brand::select('brands.code')->get()->pluck('code')->toArray();

        $datasets = [
            [
                'label' => 'Imported Units',
                'data' => [],
                'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            ],
            [
                'label' => 'Non-Imported Units',
                'data' => [],
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1
            ]
        ];

        foreach ($brands as $brand) {
            $importedUnitsCount = Stock::whereNotNull('items.customs_id')
                ->where('stocks.brand_id', Brand::where('code', $brand)->firstOrFail()->id)
                ->join('items', 'items.stock_id', '=', 'stocks.id')
                ->count();
            
            $nonImportedUnitsCount = Stock::whereNull('items.customs_id')
                ->where('stocks.brand_id', Brand::where('code', $brand)->firstOrFail()->id)
                ->leftJoin('items', 'items.stock_id', '=', 'stocks.id')
                ->whereNull('items.id')
                ->count();
            
            array_push($datasets[0]['data'], $importedUnitsCount);
            array_push($datasets[1]['data'], $nonImportedUnitsCount);
        }

        return [
            'datasets' => $datasets,
            'labels' => $brands,
        ];
    }
}
