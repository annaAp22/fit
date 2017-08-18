<?php

namespace App\Widgets;

use App\Models\Category;
use Arrilot\Widgets\AbstractWidget;

class SimilarProductsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'category_id' => 0
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $category = Category::where('id', $this->config['category_id'])->first();
        $products = $category->products()->with('attributes')->inRandomOrder()->take(10)->published()->get();
        return view("widgets.similar_products_widget", [
            'config' => $this->config, 'products' => $products
        ]);
    }
}