<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class UpdateLocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $now = Carbon::now();

        DB::table('stocks')
            ->join('items', 'stocks.id', '=', 'items.stock_id')
            ->join('schedules', 'items.schedule_id', '=', 'schedules.id')
            ->where('schedules.eta', '<=', $now)
            ->update(['stocks.location_id' => DB::raw('schedules.pod_id')]);

        DB::table('stocks')
            ->join('items', 'stocks.id', '=', 'items.stock_id')
            ->join('schedules', 'items.schedule_id', '=', 'schedules.id')
            ->where('schedules.eta', '>', $now)
            ->update(['stocks.location_id' => DB::raw('schedules.pol_id')]);

        // Update stocks location based on transportations
        DB::table('transportations')
            ->whereDate('arrival_time', '<=', $now)
            ->whereNotNull('destination_id')
            ->leftJoin('stocks', 'stocks.id', '=', 'transportations.stock_id')
            ->update(['stocks.location_id' => DB::raw('transportations.destination_id')]);

        DB::table('transportations')
            ->whereDate('arrival_time', '>', $now)
            ->whereNotNull('origin_id')
            ->leftJoin('stocks', 'stocks.id', '=', 'transportations.stock_id')
            ->update(['stocks.location_id' => DB::raw('transportations.origin_id')]);

        Log::info('Stock locations updated successfully.');
    }
}
