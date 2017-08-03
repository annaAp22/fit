<?php

namespace Tests\Feature;

use Illuminate\Contracts\Session\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;

class TestFastOrder extends TestCase
{
  /**
   * @return void
   */
  public function test() {
    $this->order();
  }
  public function fastOrder()
  {
    $response = $this->json('POST', 'ajax/order/fast', [
        'name' => 'test',
        'email' => 'system.404@yandex.ru',
        'phone' => '+79250408930',
        'rating' => '1',
        'id' => 333,
        'size' => 44,
        'quantity' => 1,
    ]);
    $response->assertJson([
        'status' => 200,
    ]);
  }
  public function order() {
    session()->forget('products.cart');
    $cart = [
        '333' => [
            '46' => [
                "cnt" => "1",
                "price" => 3990,
                "extra" => [
                    "size" => "46",
                ]
            ]
        ],
        '341' => [
            '42' => [
                "cnt" => "2",
                "price" => 3990,
                "extra" => [
                    "size" => "42",
                ]
            ]
        ],
        '200' => [
            '0' => [
                "cnt" => "1",
                "price" => 1590,
                "extra" => [
                    "size" => "0",
                ]
            ]
        ],
    ];
    session()->put('products.cart', $cart);
    $response = $this->json('POST', '/order/details', [
        'name' => 'test',
        'email' => 'system.404@yandex.ru',
        'phone' => '+79250408930',
        'rating' => '1',
        'delivery_id' => 1,
    ]);
    $response->assertJson([
        'status' => 200,
    ]);
  }

}
