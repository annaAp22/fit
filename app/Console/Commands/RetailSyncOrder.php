<?php

namespace App\Console\Commands;

use App\models\RetailOrder;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RetailSyncOrder extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'retailcrm:send_order {order?*}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send orders to retailcrm. if the order(s) is not specified, it uses the first 5 orders from retailcrm_orders table';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }
  /*
   * send order to retail crm
   * @return order id into crm or false
   * **/
  public function sendOrder($order) {
    //достаем url и ключ для подключения к crm
    $retail_api_key = env('RETAIL_CRM_API_KEY');
    $url = Setting::where('var', 'retailcrm_url')->first()->value;
    //собираем массив товаров
    $items = [];
    foreach($order->products as $product) {
      $offer = [
          'externalId' => $product->id,
      ];
      $item = [
          'offer' => $offer,
          'quantity' => $product->cnt,
      ];
      $items[] = $item;
    }
    //адрес доставки
    $address = [
        'text' => $order->address,
    ];
    //тип оплаты
    $payments = [
        'type' => isset($order->payment) ? $order->payment->sysname: null,
        'comment' => $order->payment_add,
    ];
    $retailOrderData = [
        'externalId' => $order->id,
        'firstName' => $order->name,
        'email' => $order->email,
        'phone' => $order->phone,
        'createdAt' => $order->created_at,
        'items' => $items,
        'orderType' => isset($order->extra_params['type']) ? $order->extra_params['type'] : null,
        'delivery' => array(
            'code' => isset($order->delivery) ? $order->delivery->sysname : null,
            'address' => $address,
        ),
        'payments' => $payments,
    ];
    $client = new \RetailCrm\ApiClient(
        $url,
        $retail_api_key,
        \RetailCrm\ApiClient::V5
    );
    //создаем заказ в crm
    try {
      $response = $client->request->ordersCreate($retailOrderData);
    } catch (\RetailCrm\Exception\CurlException $e) {
      Log::error($e->getMessage());
      return false;
    }

    if ($response->isSuccessful() && 201 === $response->getStatusCode()) {
      return $response->id;
    } else {
      Log::error($response->getErrorMsg());
      return false;
    }
  }
  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $orderIds = $this->argument('order');
    //
    if(isset($orderIds) && count($orderIds)) {
      $orders = RetailOrder::whereIn('order_id', $orderIds)->take(5)->get();
    }else {
      $orders = RetailOrder::take(5)->get();
    }
    $complete_orders = [];
    if(!$orders || !count($orders)) {
      $this->info('orders not found');
      return false;
    }
    foreach($orders as $order) {
      $order_id = $this->sendOrder($order);
      if($order_id) {
        $complete_orders.push($order_id);
      }
    }
    $this->info('Was created RetailCRM orders, with numbers:'. implode(',', $complete_orders));
  }
}
