<?php

namespace App\Console\Commands;

use App\Models\MsProduct;
use App\Models\Order;
use App\Models\RetailOrder;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/*
 * Команда отправляет заказы в retailcrm,
 * если указана опция log, то отправляет заказы в лог
 * если указать order, то отправляются указанный заказ.
 * Можно указывать несколко orders через пробел.
 * Сами заказы лежат в таблице для retailCRM и после успешной отправки удаляются из нее.
 * За раз отправляется по 5 заказов максимум, чтоб избежать отказа от CRM.
 * Если найдет заказ, который уже отправлялся, он так же удаляется.
 * **/
class RetailSyncOrder extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'retailcrm:send_order {order?*} {--log}';

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
  public function sendOrder($retailOrder) {
    $order = $retailOrder->order;
    //достаем url и ключ для подключения к crm
    $retail_api_key = env('RETAIL_CRM_API_KEY');
    $url = Setting::where('var', 'retailcrm_url')->first()->value;
    //собираем массив товаров
    $items = [];
    $products = $order->products;
    if(!isset($products) || !count($products)) {
      $err = 'RetailCRM Order products not found';
      Log::error($err);
      $this->info($err);
      return false;
    }
    //достаем торговое предложение, нам нужен внешний id, по которому будем искать товар
    $ms_products = MsProduct::whereIn('product_id', $products->pluck('id'))->get();
    foreach($products as $product) {
      $extra_params = $product->pivot->extra_params;
      if($extra_params) {
        $size = json_decode($extra_params)->size;
      }else {
        $size = 0;
      }
      $product_id = $ms_products->where('product_id', $product->id)->where('size', $size)->first()['ms_uuid'];
      $offer = [
          'externalId' => $product_id,
      ];
      $item = [
          'offer' => $offer,
          'quantity' => $product->pivot->cnt,
//          'properties' => [
//              'code' => 'razmer',
//              'value' => $size,
//          ],
      ];
      $items[] = $item;
    }
    //адрес доставки
    $address = [
        'text' => $order->address,
    ];
    //тип оплаты, пока не используется
//    $payments = [
//        'type' => isset($order->payment) ? $order->payment->sysname: null,
//        'comment' => $order->payment_add,
//    ];
    //$type = $order->extra_params['type'];
    $retailOrderData = [
        'externalId' => $order->id,
        'firstName' => $order->name,
        'email' => $order->email,
        'phone' => $order->phone,
        'createdAt' => $order->created_at->format('Y-m-d H:i:s'),
        'items' => $items,
        'orderMethod' => isset($order->extra_params['type']) ? $order->extra_params['type'] : null,
        'delivery' => array(
            'code' => isset($order->delivery) ? $order->delivery->sysname : null,
            'address' => $address,
        ),
        //'payments' => $payments,
    ];
    $client = new \RetailCrm\ApiClient(
        $url,
        $retail_api_key
    );
    //если включено логирование, то не отправляем в лог вместо crm
    if($this->option('log')) {
      Log::info($retailOrderData);
      $this->info('order logging');
      return false;
    }

    //создаем заказ в crm
    try {
      $response = $client->request->ordersCreate($retailOrderData);
    } catch (\RetailCrm\Exception\CurlException $e) {
      $err = 'RetailCRM '.$e->getMessage();
      Log::error($err);
      $this->info($err);
      return false;
    }

    if ($response->isSuccessful() && 201 === $response->getStatusCode()) {
      $retailOrder->delete();
      return $response->id;
    } else {
      if(isset($response['errors'])) {
        $err = 'RetailCRM '.json_encode($response['errors']);
        Log::error($err);
        $this->info($err);
      }else {
        $err = $response->getErrorMsg();
        if($err == 'Order already exists.') {
          $retailOrder->delete();
          $err = 'RetailCRM '.$err;
          $this->info($err);
          $this->info('Order is removed from the list, now');
        }else {
          $err = 'RetailCRM '.$err;
          $this->info($err);
        }
        Log::error($err);
      }
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
      $orderQ = RetailOrder::whereIn('order_id', $orderIds);
    }else {
      $orderQ = RetailOrder::query();
    }
    $orders = $orderQ->take(5)->get();
    if(!$orders || !count($orders)) {
      $this->info('orders not found');
      return false;
    }
    foreach($orders as $order) {
      $this->info('Sync order '. $order->order_id);
      $order_id = $this->sendOrder($order);
      if($order_id) {
        $this->info(' success, was created RetailCRM order:'.$order_id);
      }
    }
  }
}
