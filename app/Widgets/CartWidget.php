<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Product;

class CartWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'class_basket_info' => 'basket-info ef in-4  ac-2 hv-4 tr-1 active-listener',
        'class_open' => 'open btn m-2 a-v-c to-r ace-1 he-1 icon-wr a-c',
        'class_t' => true
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $cart = session()->get('products.cart');
        if($cart) {
            $cart['products'] = Product::whereIn('id', array_keys($cart))->where('status', 1)->get();
            $cart['count'] = count($cart['products']);
            $amount = 0;
            foreach($cart['products'] as $product)
            {
                $amount += $product->price * $cart[$product->id]['cnt'];
            }
            //$cart['amount'] = $cart['products']->sum('price');
            $cart['amount'] = $amount;
        }

        return view("widgets.cart_widget", [
            'config' => $this->config, 'cart' => $cart
        ]);
    }
}