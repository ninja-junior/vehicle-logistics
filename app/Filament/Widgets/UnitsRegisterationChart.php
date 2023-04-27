<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\Registration;
use Filament\Widgets\BarChartWidget;

class UnitsRegisterationChart extends BarChartWidget
{
    protected static ?string $heading = 'Units Registeration by Brand';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $brands = Brand::select('brands.code')->get()->pluck('code')->toArray();
        $start = Carbon::now()->subMonth(12)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $datasets = [];
        $colors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)'
        ]; // example array of colors
        $i = 0; // counter for accessing colors array
        foreach ($brands as $brand) {
            $soldData = [];
            $dates = [];

            $stocks = Stock::where('brand_id', Brand::where('code', $brand)->firstOrFail()->id)->get();
            foreach ($stocks as $stock) {
                $soldCount = Registration::where('stock_id', $stock->id)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();

                array_push($soldData, $soldCount);
                array_push($dates, $stock->created_at->format('M j'));
            }   

            array_push($datasets, [
                'label' => $brand,
                'data' => $soldData,
                'fill' => false,
                'backgroundColor' => $colors[$i],
                'borderColor' => $colors[$i], // assign color from array
                'tension' => 0.1,
            ]);
            $i++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $dates,
        ];
    }
}
