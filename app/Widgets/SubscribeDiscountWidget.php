<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class SubscribeDiscountWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'class' => 'main-content-3',
        'form_index' => '-2',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view("widgets.subscribe_discount_widget", [
            'config' => $this->config,
        ]);
    }
}