<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class HeaderBasket extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 0,
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $cart = session()->get('products.cart');

        if($cart)
        {
            foreach( $cart as $items ) {
                $this->config['count'] += count($items);
            }
        }

        return view("widgets.header_basket", [
            'config' => $this->config,
        ]);
    }
}