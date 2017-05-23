<?php

namespace App\Widgets;

use App\Models\Category;
use App\Models\Page;
use Arrilot\Widgets\AbstractWidget;

class FooterList extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'type' => 'info',
        'page_title' => '',
    ];
    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = 60;
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $pages = Page::type($this->config['type'])->get();
        return view("widgets.footer_list", [
            'config' => $this->config,
            'pages' => $pages,
        ]);
    }
}