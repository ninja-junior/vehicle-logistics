<?php

namespace App\Filament\Widgets;

use App\Models\Brand;
use App\Models\Sale;
use App\Models\Stock;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;

class UnitsSoldChart extends LineChartWidget
{
    protected static ?string $heading = 'Units Sold and Available by Brand';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $brands = Brand::select('brands.code')->get()->pluck('code')->toArray();
        $start = Carbon::now()->subMonth(6)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $datasets = [];
        foreach ($brands as $brand) {
            $soldData = [];
            $availableData = [];
            $dates = [];

            $stocks = Stock::where('brand_id', Brand::where('code', $brand)->firstOrFail()->id)->get();
            foreach ($stocks as $stock) {
                $soldCount = Sale::where('stock_id', $stock->id)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
                $availableCount = Sale::where('stock_id', $stock->id)
                    ->where('created_at', '<', $start)
                    ->count();

                array_push($soldData, $soldCount);
                array_push($availableData, $availableCount);
                array_push($dates, $stock->created_at->format('M j'));
            }

            array_push($datasets, [
                'label' => $brand . ' - Sold',
                'data' => $soldData,
                'fill' => false,
                'borderColor' => 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).', 1)',
                'tension' => 0.1,
            ]);

            array_push($datasets, [
                'label' => $brand . ' - Available',
                'data' => $availableData,
                'fill' => false,
                'borderColor' => 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).', 1)',
                'tension' => 0.1,
            ]);
        }

        return [
            'datasets' => $datasets,
            'labels' => $dates,
        ];
    }
}