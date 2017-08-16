<?php

namespace App\Http\Controllers;

use App\Models\MsOrder;
use App\Library\MoySklad\Ms;
use App\Models\MsProduct;
use App\Models\MsCronCounter;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\MsAgent;

class MoySkladController extends Controller
{
  // Direction: site -> moysklad.
  // Exports new orders.
  // Old name: cron.
  public function exportOrders(Ms $ms)
  {

    // Check new orders
    $msOrders = MsOrder::noErrors()->get();

    $resultMessage = '';

    if($msOrders->count())
    {
      $lastOrder = $ms->lastOrder()->rows[0];
      $lastOrderId = isset($lastOrder->name) ? intval($lastOrder->name) : 0;

      foreach ($msOrders as $key => $msOrder)
      {
        $newOrderId = sprintf('%05d', $lastOrderId + $key + 1);
        $order['name'] = $newOrderId;

        $des = json_decode($msOrder->ms_description);
        $order['description'] = "Заказ №: ". $des->order_id . "; ФИО: ". $des->name ."; E-mail: ". $des->email ."; Телефон: ". $des->phone . "; Адрес доставки: ". $des->address . "; Доставка(". $des->delivery . ")";

        $order['organization'] = [
            "meta" => [
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/organization/" . Ms::TARGET,
                "type" => "organization",
                "mediaType" => "application/json"
            ]
        ];

        // Create new agent if already not exists
        $agentId = null;
        if( !$msOrder->ms_agent_id)
        {
          $postData = json_encode([
              'name' => $des->name,
              'email' => $des->email == "no email" ? '' : $des->email,
              'phone' => $des->phone,
              'actualAddress' => $des->address ? $des->address : '',
          ]);
          $agent = $ms->newAgent($postData);
          if( !isset($agent->id) )
            return "Неудалось создать нового контрагента";
          $agentId = $agent->id;

          // Save new agent
          $msAgent = new MsAgent();
          $msAgent->ms_uuid = $agent->id;
          $msAgent->ms_name = $agent->name;
          $msAgent->ms_tag= json_encode($agent->tags);
          $msAgent->ms_phone = $agent->phone;
          $msAgent->ms_email = isset($agent->email) ? $agent->email : null;
          $msAgent->save();



          // Save agent id in ms_order for preventing multiple agent creation bug
          $msOrder->ms_agent_id = $agent->id;
          $msOrder->save();
        }
        else
        {
          $agentId = $msOrder->ms_agent_id;
        }

        $order["agent"] = [
            "meta" => [
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty/". $agentId,
                "type" => "counterparty",
                "mediaType" => "application/json"
            ]
        ];

        $order["store"] = [
            "meta" => [
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/store/" . Ms::STORE,
                "type" => "store",
                "mediaType" => "application/json"
            ]
        ];


        $order["attributes"] = [
            [
              // Source
                "id" => "ed4889ec-f0ab-11e3-854e-002590a28eca",
                "value" => [
                    'meta' => [
                        'href' => 'https://online.moysklad.ru/api/remap/1.1/entity/customentity/d155f4f8-f0ab-11e3-cde8-002590a28eca/114918af-f0ac-11e3-a4e8-002590a28eca',
                        'metadataHref' => 'https://online.moysklad.ru/api/remap/1.1/entity/companysettings/metadata/customEntities/d155f4f8-f0ab-11e3-cde8-002590a28eca',
                        'type' => 'customentity',
                        'mediaType' => 'application/json',
                        'uuidHref' => 'https://online.moysklad.ru/app/#custom_d155f4f8-f0ab-11e3-cde8-002590a28eca/edit?id=114918af-f0ac-11e3-a4e8-002590a28eca',
                    ],
                    'name' => 'Сайт',
                ],
            ],
            [
                "id" => "c78108c8-d4da-11e4-be18-0cc47a419cc0",
                "value" => [
                    "meta" => [
                        'href' => 'https://online.moysklad.ru/api/remap/1.1/entity/employee/0aac7a3e-021e-11e4-8d70-002590a28eca',
                        'metadataHref' => 'https://online.moysklad.ru/api/remap/1.1/entity/employee/metadata',
                        'type' => 'employee',
                        'mediaType' => 'application/json',
                        'uuidHref' => 'https://online.moysklad.ru/app/#employee/edit?id=0aac7a3e-021e-11e4-8d70-002590a28eca',
                    ]
                ],
                "name" => "API",
            ]
        ];

        $order["positions"] = json_decode($msOrder->ms_positions);

        $postData = json_encode($order);
        $res = $ms->postOrder($postData);
        if( isset($res->errors))
        {
            $msOrder->error = 1;
            $msOrder->error_message = $res->errors;
            $msOrder->save();
            $resultMessage .= 'Ошибка в заказе №:' . $msOrder->id . ' ' . $res->errors[0]->error . '/r/n';
        }
        else
        {
            $resultMessage .= 'Заказ №: ' . $msOrder->id . ' успешно импортирован/r/n';
            $msOrder->delete();
        }
      }
    }
    else
    {
      return "Нет новых заказов";
    }

    return $resultMessage;

  }

  // Direction: moysklad -> site.
  // Updates prices and stock.
  // Old name: cron2.
  public function updatePriceAndStock(Ms $ms)
  {
    $paramsString = http_build_query([
        'offset' => 0,
        'limit' => 100,
    ]);
    $rests = [];
    $res = $ms->getStock($paramsString);

    if( $res && isset($res->meta->limit, $res->meta->size) )
    {
      $rests = array_merge($rests, $res->rows);
      $limit = $res->meta->limit;
      $size = $res->meta->size;
      // If products total count > limit by one request,
      // then do another request with offset
      for( $offset = $limit; $offset < $size; $offset = $offset + $limit )
      {
        $paramsString = http_build_query([
            'offset' => $offset,
            'limit'  => $limit,
        ]);
        if( $res = $ms->getStock($paramsString) )
        {
          $rests = array_merge($rests, $res->rows);
        }

      }
    }

    if($rests)
    {
      Product::where('stock', 1)
          ->update(['stock' => 0]);
    }

    $products = [];
    $msProducts = MsProduct::all();
    $msProductsAr = [];
    foreach ($msProducts as $msProduct)
    {
      $msProductsAr[$msProduct->ms_externalCode] = $msProduct;
    }

    $sizes = Attribute::where('name', "Размеры")->first();
    $allSizes = Attribute::where('name', "Все размеры")->first();
//        $sizesAr = explode(",", str_replace(["[", "]"], "", $sizes->value));
    $sizesAr = json_decode($sizes->list);

    foreach ($rests as $rest)
    {
      if( isset($msProductsAr[$rest->externalCode]) )
      {
        $product_id = $msProductsAr[$rest->externalCode]->product_id;
        // Product
        if($rest->meta->type == 'product')
        {
          $products[$product_id]['salePrice'] = $rest->salePrice / 100;
          $products[$product_id]['quantity'] = $rest->quantity;
        }
        // Sizes
        elseif( isset($rest->code) && strpos($rest->code, "-") )
        {
          $products[$product_id]['salePrice'] = $rest->salePrice / 100;
          $products[$product_id]['quantity'] = $rest->quantity;
          $size = last(explode("-", $rest->code));
          // Skip all non predefined sizes
          if($rest->quantity > 0) {
            if(in_array($size, $sizesAr))
              $products[$product_id]['sizes'][] = $size;
          }
          if(in_array($size, $sizesAr))
            $products[$product_id]['allSizes'][] = $size;
        }
      }
    }

    //Reset all size attributes
    \DB::table('attribute_product')
        ->where('attribute_id', $sizes->id)->orWhere('attribute_id', $allSizes->id)
        ->delete();

    foreach ($products as $id => $msProduct)
    {
      $product = Product::find($id);
      $product->price = (100 - $product->discount)/100 * $msProduct['salePrice'];
      $product->stock = 1;
      if(isset($msProduct['sizes']))
      {
        $product->attributes()->attach($sizes->id, [
            'value' => json_encode($msProduct['sizes']),
        ]);
      }
      if(isset($msProduct['allSizes']))
      {
        $product->attributes()->attach($allSizes->id, [
            'value' => json_encode($msProduct['allSizes']),
        ]);
      }
      $product->save();
    }

    return "Получено остатков: " . count($rests);

  }

  // Direction: moysklad -> site.
  // Imports new products by portion of 100, updates old data in moysklad products table, site products table stays unchanged.
  // Old name: cron3.
  public function importProducts(Ms $ms)
  {
    // Current import start index and total count
    $counter = MsCronCounter::importProducts()->firstOrFail();

    $paramsString = http_build_query([
        'offset' => $counter->offset,
        'limit'  => $counter->limit,
        'orderBy' => 'ProductFolder',
//                'updatedFrom' => $counter->updated_at->toDateTimeString(),
    ]);

    $products = [];

    if( $res = $ms->importProducts($paramsString) )
    {
      $products = array_merge($products, $res->rows);
      // If products total count > limit by one request,
      // then do another request with offset
      for( $offset = $res->meta->limit; $offset < $res->meta->size; $offset = $offset + $res->meta->limit )
      {
        $paramsString = http_build_query([
            'offset' => $offset,
            'limit'  => $counter->limit,
            'orderBy' => 'ProductFolder',
//                        'updatedFrom' => $counter->updated_at->toDateTimeString(),
        ]);
        if( $res = $ms->importProducts($paramsString) )
        {
          $products = array_merge($products, $res->rows);
        }

      }
    }


    // Set last update date
    $counter->updated_at = Carbon::now();
    $counter->save();

    // Clear products sync table
    MsProduct::truncate();

    // Get site products id and sku
    $siteProducts = Product::select(['id', 'sku'])->get();
    $temp = [];
    foreach ($siteProducts as $product)
    {
      $temp[$product->sku] = $product->id;
    }
    $siteProducts = $temp;

        $syncProducts = [];
        $salePrice = 0;
        //Insert products to db
        foreach( $products as $product )
        {
            if( isset($product->code) )
            {
                if( isset($siteProducts[$product->code]) )
                {
                    $salePrice = isset($product->salePrices) ?   $product->salePrices[0]->value / 100 : $salePrice;
                    // Simple products and products of some color
                    $syncProducts[] = [
                        'product_id' => $siteProducts[$product->code],
                        'ms_sku' => $product->code,
                        'ms_uuid' => $product->id,
                        'ms_type' => $product->meta->type,
                        'ms_externalCode' => $product->externalCode,
                        'ms_quantity' => $product->quantity > 0 ? $product->quantity : 0,
                        'ms_salePrice' => $salePrice,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                elseif( isset($product->characteristics) /*&& count($product->characteristics) > 1*/ ){
                    //If modifications has color and size simultaneously
                    $code = explode('-', $product->code)[0];
                    if( isset($siteProducts[$code]) )
                    {
                        $syncProducts[] = [
                            'product_id' => $siteProducts[$code],
                            'ms_sku' => $product->code,
                            'ms_uuid' => $product->id,
                            'ms_type' => $product->meta->type,
                            'ms_externalCode' => $product->externalCode,
                            'ms_quantity' => $product->quantity > 0 ? $product->quantity : 0,
                            'ms_salePrice' => $salePrice,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }

    if($syncProducts)
    {
      //добавляем размеры для быстрого поиска торговых предложений
      foreach ($syncProducts as $key=>$syncProduct) {
        if($syncProduct['ms_type'] == 'variant') {
          $code_parts = explode('-', $syncProduct['ms_sku']);
          if(count($code_parts) > 1) {
            $size = end($code_parts);
            if($size < '30') {
              $size = '0';
            }
          }else {
            $size = '0';
          }
          $syncProducts[$key]['size'] = $size;
        } else {
          $syncProducts[$key]['size'] = null;
        }
      }
      MsProduct::insert($syncProducts);
    }
    return "Products synced count: " . count($syncProducts);

  }

  // Direction moysklad -> site.
  // Imports customers from moysklad
  // Old name: cron4.
  public function importAgents(Ms $ms)
  {
    //        MsAgent::truncate();
//        set_time_limit(600);
    $of = MsAgent::count();

    $paramsString = http_build_query([
        'offset' => $of,
        'limit' => 100,
    ]);

    $agents = [];
    if( $res = $ms->getAgents($paramsString) )
    {
      $agents = array_merge($agents, $res->rows);
      $size = $res->meta->size;

      for( $offset = $of + 100; $offset <= $of + 100 + 800; $offset = $offset + 100 )
      {
        $paramsString = http_build_query([
            'offset' => $offset,
            'limit'  => 100,
        ]);
        if( $res = $ms->getAgents($paramsString) )
        {
          $agents = array_merge($agents, $res->rows);
        }
      }
    }

    $syncAgents = [];
    foreach ($agents as $agent)
    {
      $syncAgents[] = [
          'ms_uuid' => $agent->id,
          'ms_name' => isset($agent->name) ? $agent->name : null,
          'ms_tag' => json_encode($agent->tags),
          'ms_phone' => isset($agent->phone) ? $agent->phone : null,
          'ms_email' => isset($agent->email) ? $agent->email : null,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
      ];
    }
    MsAgent::insert($syncAgents);



    return "Загружено конртрагентов: " . (count($syncAgents) + $of);

  }

  public function updateAgents(Ms $ms)
  {
    //        MsAgent::truncate();
//        set_time_limit(600);
    $task = MsCronCounter::where('action', 'import_agents')->firstOrFail();

    $paramsString = http_build_query([
        'offset' => 0,
        'limit' => 100,
        'updatedFrom' => $task->updated_at->toDateTimeString(),
    ]);

    $agents = [];
    if( $res = $ms->getAgents($paramsString) )
    {
      $agents = array_merge($agents, $res->rows);
      $size = $res->meta->size;

      for( $offset = 100; $offset <= $size; $offset = $offset + 100 )
      {
        $paramsString = http_build_query([
            'offset' => $offset,
            'limit'  => 100,
            'updatedFrom' => $task->updated_at->toDateTimeString(),
        ]);
        if( $res = $ms->getAgents($paramsString) )
        {
          $agents = array_merge($agents, $res->rows);
        }
      }
    }

    $syncAgents = [];

    foreach ($agents as $agent)
    {
      // If agent not already in db then add new
      $msAgent = MsAgent::where('ms_uuid', $agent->id)->first();

      if( !$msAgent )
      {
        $syncAgents[] = [
            'ms_uuid' => $agent->id,
            'ms_name' => isset($agent->name) ? $agent->name : null,
            'ms_tag' => json_encode($agent->tags),
            'ms_phone' => isset($agent->phone) ? $agent->phone : null,
            'ms_email' => isset($agent->email) ? $agent->email : null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
      }
    }

    // Set last update time
    $task->updated_at = date('Y-m-d H:i:s');
    $task->save();

    // Insert new agents
    if($syncAgents)
      MsAgent::insert($syncAgents);

    return "Загружено конртрагентов: " . (count($syncAgents));

  }

  // Direction moysklad -> site.
  // Updates reservation option status.
  public function updateReservation()
  {

  }

  public function getOrder(Ms $ms, $id)
  {
    $res = $ms->getOrderById($id);
    return $res;
  }
}
