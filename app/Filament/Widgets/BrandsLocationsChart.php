<?php

namespace App\Filament\Widgets;

use App\Models\Brand;
use App\Models\Stock;
use App\Models\Location;
use Filament\Widgets\BarChartWidget;

class BrandsLocationsChart extends BarChartWidget
{
    
    protected static ?string $heading = 'Brand and Location Report';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $brands = Brand::select('brands.code')->get()->pluck('code')->toArray();
        $locations = Location::select('locations.code')->where('code', '<>', 'delivered')->get()->pluck('code')->toArray();

        $datasets = [];
        $colors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)'
        ];
        $colorIndex = 0;
        foreach ($brands as $brand) {
            $data = [];
            foreach ($locations as $location) {
                $count = Stock::where('location_id', Location::where('code', $location)->first()->id)
                    ->where('brand_id', Brand::where('code', $brand)->first()->id)
                    ->count();
                array_push($data, $count);
            }
            array_push($datasets, [
                'label' => $brand,
                'data' => $data,
                'backgroundColor' => $colors[$colorIndex],
                'borderColor' => 'rgba(0, 0, 0, 1)',
                'borderWidth' => 1
            ]);
            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $locations,
        ];
    }
}