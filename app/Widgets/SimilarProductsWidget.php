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
        'product' => null
    ];
    //public $cacheTime = 60;
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if(!$this->config['product']) {
            return '';
        }
        $category_id = $this->config['product']->categories->first()->id;
        $category = Category::find($category_id);
        $products = $category->products()->where('product_id', '<>', $this->config['product']->id)->withInfo()->inRandomOrder()->take(10)->published()->get();
        return view("widgets.similar_products_widget", [
            'config' => $this->config, 'products' => $products
        ]);
    }
}