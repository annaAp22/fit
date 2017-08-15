<?php

namespace App\Widgets;

use App\Models\Product;
use Arrilot\Widgets\AbstractWidget;

class ProductsSliderWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'type' => 'default',
        'take' => 10,
        'id' => 'js-product-set',
    ];
    public $cacheTime = 60;
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $query = $products = Product::published()->take($this->config['take'])->with('attributes');
        if($this->config['type'] == 'hit') {
            $query->where('hit', 1);
        }
        elseif($this->config['type'] == 'act') {
            $query->where('act', 1);
        }
        elseif($this->config['type'] == 'new') {
            $query->where('new', 1);
        }
        $products = $query->orderByRaw('rand()')->get();
        return view('widgets.products_slider_widget', [
            'config' => $this->config,
            'products' => $products,
        ]);
    }
}
