<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class LinksStickerWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'class_div' => 'pros',
        'class_a' => 'p-item-2'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view("widgets.links_sticker_widget", [
            'config' => $this->config,
        ]);
    }
}