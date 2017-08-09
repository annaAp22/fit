<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\MsAgent;
use App\Models\MsOrder;
use App\Models\MsParam;
use App\Models\RetailOrder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Product;
use App\Models\Order;

use Illuminate\Support\Facades\Mail;
use Validator;

class OrderController extends Controller
{
  /**
   * Корзина
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function cart() {
    $cart = [
        'products' => collect()
    ];

    // Clear session if needed
//        session()->flush();
    if(session()->has('products.cart')) {
      $cart = session()->get('products.cart');
      //товары коллекции
      $products = Product::whereIn('id', array_keys($cart))->published()->with(['related', 'related.attributes'])->get();
      //с товарами покупают
      $related = collect();

      $cart_products = collect();
      foreach($cart as $product_id => $sizes)
      {
        foreach($sizes as $size => $item )
        {
          $product = clone $products->where('id', $product_id)->first();
          $product->size = $size;
          $product->count = $item['cnt'];
          $product->amount = $item['cnt'] * $product->price;
          $product->extra_params = collect($item['extra']);

          $cart_products->push($product);
          $related = $related->merge($product->related);
        }
      }
      $cart['products'] = $cart_products;
      $cart['related'] = $related;

      $cart['related'] = $cart['related']->unique('id')->reject(function ($item, $key) use ($cart) {
        $id = $item->id;
        return $cart['products']->search(function ($item2, $key2) use ($id) {
              return $item2->id == $id;
            })!==false;
      });
      $cart['amount'] = $cart['products']->sum('amount');
    }

    $this->setMetaTags();
    return view('order.cart', $cart);
  }

  /**
   * Оформление заказа - выбор доставки и оплаты
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function order(Request $request) {
    if(!session()->has('products.cart')) {
      return redirect()->route('cart');
    }

    $cart = session()->get('products.cart');
    $products = Product::whereIn('id', array_keys($cart))->published()->get();
    $cart_products = collect();

    foreach($cart as $product_id => $sizes)
    {
      foreach($sizes as $size => $item )
      {
        $product = clone $products->where('id', $product_id)->first();
        $product->size = $size;
        $product->count = $item['cnt'];
        $product->amount = $item['cnt'] * $product->price;

        $cart_products->push($product);
      }
    }
    $cart['products'] = $cart_products;
    $cart['amount'] = $cart['products']->sum('amount');



    //способы доставки
    $deliveries = \App\Models\Delivery::where('status', 1)->orderBy('id')->get();
    //способы оплаты
    $payments = \App\Models\Payment::where('status', 1)->orderBy('name')->get();

    $this->setMetaTags();
    return view('order.order', ['cart' => $cart, 'deliveries' => $deliveries, 'payments' => $payments]);
  }

  /**
   * Сохранение заказа
   * @param Requests\DeliveryRequest $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function details(\App\Http\Requests\DeliveryRequest $request) {
    if(!session()->has('products.cart')) {
      return redirect()->route('cart');
    }

    $data = $request->input();

    $data['email'] = $data['email'] ?: 'no email';

    $data['datetime'] = date('Y-m-d H:i:s');
    $data['status'] = 'wait';
    if(isset($data['delivery_id'])) {
      $delivery = Delivery::where('id', $data['delivery_id'])->published()->first();
    }
    if($delivery) {
      $data['extra_params'] = ['delivery_price' => $delivery->price];
    }

    //$data['payment_add'] = 'Почтовый индекс: '.$data['index'].'. Комментарий: '.$data['payment_add'];
    $order = Order::create($data);
    $amount = 0;
    foreach( session()->get('products.cart') as $product_id => $items )
    {
      foreach( $items as $size => $product )
      {
        if(isset($product['extra']['_token']))
        {
          unset($product['_token']);
        }
        $order->products()->attach($product_id, [
            'cnt'          => $product['cnt'],
            'price'        => $product['price'],
            'extra_params' => isset($product['extra']) ? json_encode($product['extra']) : '',
        ]);
        $amount += $product['cnt']*$product['price'];
      }
    }
     $order->update(['amount' => $amount]);
    //если включена опция локалка, то не используем мой склад
    if(!env('IS_LOCALHOST', 0)) {
        // Add new order to moySklad orders table
       $msOrder = new MsOrder();
       $msOrder->ms_description = json_encode([
           'order_id' => $order->id,
           'name' => $order->name,
           'email' => $order->email,
           'phone' => $order->phone,
           'address' => $order->address,
           'delivery' => $order->delivery->name,
       ]);

       $positions = [];
       foreach ($order->products as $product)
       {
           $params = json_decode($product->pivot->extra_params);
           $sku = $params->size ? $product->sku . "-" . $params->size : $product->sku;
           $ms_product = $product->ms_products()->where('ms_sku', $sku)->first();
           if($ms_product)
           {
              $positions[] = [
                 "quantity" => intval($product->pivot->cnt),
                 "price" => (floatval($product->price) / (100 - $product->discount))  * 100 * 100,
                 "discount" => floatval($product->discount),
                 "vat" => 0,
                 "assortment" => [
                     "meta" => [
                         "href" => "https://online.moysklad.ru/api/remap/1.1/entity/". $ms_product->ms_type ."/" . $ms_product->ms_uuid,
                         "type" => $ms_product->ms_type,
                         "mediaType" => "application/json"
                     ]
                 ],
                 "reserve" => floatval(MsParam::reservation()->first()->value),
             ];
           }
       }

       // // Search agent
       $phoneVariants = [
           $order->phone,
           str_replace([' ', '+'], '', $order->phone),
           str_replace([' ', '7', '+'], ['','8', ''], $order->phone ),
           str_replace([' ', '7', '+'], '', $order->phone),
       ];
       if( $agent = MsAgent::whereIn('ms_phone', $phoneVariants)
           ->orWhere(function($query) use($order){
               $query->whereNotNull('ms_email')
                   ->where('ms_email', $order->email);
           })
           ->first() )
       {
           $msOrder->ms_agent_id = $agent->ms_uuid;
       }


       $msOrder->ms_positions = json_encode($positions);
       $msOrder->save();
    }
    //сохраняем заказ в таблицу для retailcrm
    RetailOrder::create(['order_id' => $order->id]);
    //отправка почты, может быть отключена в настройках
    if(!env('MAIL_DISABLED')) {
      $phone = \App\Models\Setting::getVar('phone_number')['free'];
      //send order to mail
      Mail::send('emails.order',
          [
              'order' => $order,
          ], function ($message) use ($request) {
            $email = \App\Models\Setting::getVar('email_support');
            $caption = 'Заказ';
            $message->to($email)->subject($caption);
          });
      $siteUrl = env('DEV_SITE_URL', $request->root());
      //send order to user mail
      Mail::send('emails.order_for_user',
          [
              'order' => $order,
              'phone' => strip_tags($phone),
              'siteUrl' => $siteUrl,
          ], function ($message) use ($request) {
            $caption = 'Ваш заказ с сайта fit2u';
            $message->to($request->input('email'))->subject($caption);
          });
    }
    //чистим сессию корзины, можно отключить в опциях
    if(!env('CART_CLEAR_DISABLED', 0)) {
      session()->forget('products.cart');
      session()->flash('products.order.id', $order->id);
      session()->flash('products.order.name', $order->name);
    }
    $res['html'] = view('order.partials.success', ['order_id' => $order->id])->render();
    $res['action'] = 'orderSuccess';
    $res['status'] = 200;
    return $res;
  }

  /**
   * Страница благодарности за заказ
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
   */
  public function confirm() {
    if(!session()->has('products.order')) {
      return redirect()->route('cart');
    }

    $this->setMetaTags();
    return view('order.confirm', [
        'order_id' => session()->get('products.order.id'),
        'customer' => session()->get('products.order.name'),
    ]);
  }
}
