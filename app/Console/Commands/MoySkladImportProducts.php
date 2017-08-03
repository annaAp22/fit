<?php

namespace App\Console\Commands;

use App\Http\Controllers\MoySkladController;
use App\Library\MoySklad\Ms;
use Illuminate\Console\Command;

class MoySkladImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moysklad:import_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from Moy sklad';

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
      $skladController = new MoySkladController();
      $result = $skladController->importProducts(new Ms());
      $this->info($result);
    }
}
