<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;

class BannerLeftWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'sex' => 'woman',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $banners = Banner::where('type', 'left')->where('status', 1)->get();
        //заменяем ?sex в ссылках на пол для заданной страницы
        foreach ($banners as $item) {
            $item->url = str_replace('?sex', $this->config['sex'], $item->url);
        }
        return view("widgets.banner_left_widget", [
            'config' => $this->config,
            'banners' => $banners,
        ]);
    }
}