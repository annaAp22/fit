<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Category;

class CatalogWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'marker_down' => false
    ];

    // Cache
    public $cacheTime = 60;

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $categories = Category::with([
                'children' => function($query) {
                    $query->published()->sorted();
                },
                'children.children' => function($query) {
                    $query->published()->sorted();
                }
            ])
            ->roots()
            ->published()
            ->sorted()
            ->get();

        return view("widgets.catalog_widget", [
            'config' => $this->config,
            'categories' => $categories,
        ]);
    }
}