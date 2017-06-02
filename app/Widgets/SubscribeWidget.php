<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class SubscribeWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'class' => '',
        'form_index' => '-2',
        'caption_class' => 'caption-3',
        'total_class' => 'total-c-9',
        'total_icon_right' => false,
    ];

    public $cacheTime = 60;

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view("widgets.subscribe_widget", [
            'config' => $this->config,
        ]);
    }
}