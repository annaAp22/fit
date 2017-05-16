<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Product;
use App\Models\Order;
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
        if(session()->has('products.cart')) {
            $cart = session()->get('products.cart');
            //товары коллекции
            $cart['products'] = Product::whereIn('id', array_keys($cart))->where('status', 1)->with(['related', 'related.attributes'])->get();
            //с товарами покупают
            $cart['related'] = collect();
            foreach($cart['products'] as $key => $product) {
                $product->count = $cart[$product->id]['cnt'];
                $product->amount = $cart[$product->id]['cnt'] * $product->price;
                $product->extra_params = collect(session()->get('products.cart.'.$product->id.'.extra'));

                $cart['related'] = $cart['related']->merge($product->related);
            }
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
        //товары коллекции
        $cart['products'] = Product::whereIn('id', array_keys($cart))->where('status', 1)->get();
        foreach($cart['products'] as $key => $product) {
            $product->count = $cart[$product->id]['cnt'];
            $product->amount = $cart[$product->id]['cnt'] * $product->price;
        }
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

        //$data['payment_add'] = 'Почтовый индекс: '.$data['index'].'. Комментарий: '.$data['payment_add'];

        $order = Order::create($data);
        $amount = 0;
        foreach(session()->get('products.cart') as $product_id => $product) {
            $order->products()->attach($product_id, [
                'cnt'          => $product['cnt'],
                'price'        => $product['price'],
                'extra_params' => isset($product['extra']) ? json_encode($product['extra']) : '',
            ]);
            $amount += $product['cnt']*$product['price'];
        }
        $order->update(['amount' => $amount]);

        session()->forget('products.cart');

        session()->flash('products.order.id', $order->id);
        session()->flash('products.order.name', $order->name);

        $res['html'] = view('order.partials.success', ['order_id' => $order->id])->render();
        $res['action'] = 'orderSuccess';
        return $res;

//        return redirect()->route('order.confirm');
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
