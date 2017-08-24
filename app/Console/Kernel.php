<?php

namespace App\Console;

use App\Console\Commands\MoySkladImportProducts;
use App\Console\Commands\Phone;
use App\Console\Commands\ProductsSex;
use App\Console\Commands\RetailSyncOrder;
use App\Console\Commands\RetailUnsyncOrders;
use App\Console\Commands\TestOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        RetailSyncOrder::class,
        MoySkladImportProducts::class,
        RetailUnsyncOrders::class,
        ProductsSex::class,
        Phone::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // Export customers orders to MoySklad
        $schedule->call('App\Http\Controllers\MoySkladController@exportOrders')
            ->name('export-orders')
            ->withoutOverlapping();
        // Update stock rests
        $schedule->call('App\Http\Controllers\MoySkladController@updatePriceAndStock')
            ->hourly();
        // Truncate database and import products from MoySklad
        $schedule->call('App\Http\Controllers\MoySkladController@importProducts')->dailyAt('4:00');
        // Import new agents
        $schedule->call('App\Http\Controllers\MoySkladController@updateAgents')->hourly();
        //Export orders to retailrcrm
        $schedule->command('retailcrm:send_order')->everyMinute();
   
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
