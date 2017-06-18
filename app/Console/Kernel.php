<?php

namespace App\Console;

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
            ->everyMinute()
            ->name('exportOrders')
            ->withoutOverlapping();

        // Update stock rests
        $schedule->call('App\Http\Controllers\MoySkladController@updatePriceAndStock')
            ->hourly();

        // Truncate database and import products from MoySklad
        $schedule->call()->dailyAt('4:00');
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
