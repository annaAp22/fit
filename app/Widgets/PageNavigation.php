<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class PageNavigation extends AbstractWidget
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
        // Get info pages
        $info = \App\Models\Page::info()->get();

        // Get help pages
        $help = \App\Models\Page::help()->get();

        return view("widgets.page_navigation", [
            'config' => $this->config,
            'info' => $info,
            'help' => $help,
        ]);
    }
}