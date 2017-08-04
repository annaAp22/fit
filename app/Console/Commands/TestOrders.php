<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeorder:products {--fast}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send order with any products';

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
//      session()->forget('products.cart');
//      $cart = [
//          '333' => [
//              '46' => [
//                  "cnt" => "1",
//                  "price" => 3990,
//                  "extra" => [
//                      "size" => "46",
//                  ]
//              ]
//          ],
//          '341' => [
//              '42' => [
//                  "cnt" => "2",
//                  "price" => 3990,
//                  "extra" => [
//                      "size" => "42",
//                  ]
//              ]
//          ],
//          '200' => [
//              '0' => [
//                  "cnt" => "1",
//                  "price" => 1590,
//                  "extra" => [
//                      "size" => "0",
//                  ]
//              ]
//          ],
//      ];
//      session()->put('products.cart', $cart);
//      if($this->option('fast')) {
//
//      } else {
//
//        $response = response()->json('POST', '/order/details', [
//            'name' => 'test',
//            'email' => 'system.404@yandex.ru',
//            'phone' => '+79250408930',
//            'rating' => '1',
//            'delivery_id' => 1,
//        ]);
//      }
//      $response->assertJson([
//          'status' => 200,
//      ]);
   }
}
