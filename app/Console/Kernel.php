<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
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
        //
      $schedule->call('App\Http\Controllers\MoySkladController@test')->everyMinute();

      Log::info('cron there was');
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
