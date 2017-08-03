<?php

namespace App\Console\Commands;

use App\Models\RetailOrder;
use Illuminate\Console\Command;

class RetailUnsyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retailcrm:show_unsync_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show unsync orders for RetailCRM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
      $orders = RetailOrder::get();
      if(isset($orders) && count($orders)) {
        $this->info('Orders:'.implode(',', $orders->pluck('order_id')->toArray()));
      }else {
        $this->info('Orders not found');
      }

    }
}
