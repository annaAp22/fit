<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Product;

class ViewProductsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'product_id' => 0
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if(!session()->has('products.view')) {
            return '';
        }
        $ids = session()->get('products.view');

        if($this->config['product_id']) {
            $ids = array_except($ids, [$this->config['product_id']]);
        }

        $products = Product::withInfo()->whereIn('id', array_keys($ids))->where('status', 1)->get();

        return view("widgets.view_products_widget", [
            'config' => $this->config, 'products' => $products
        ]);
    }
}