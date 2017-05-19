<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Banner;

class BannerLeftWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $banners = Banner::where('type', 'left')->where('status', 1)->get();

        return view("widgets.banner_left_widget", [
            'config' => $this->config,
            'banners' => $banners,
        ]);
    }
}