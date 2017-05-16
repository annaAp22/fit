<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Brand;

class BrandsWidget extends AbstractWidget
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
        $brands = Brand::where('status', 1)->orderBy('name')->get();
        return view("widgets.brands_widget", [
            'config' => $this->config, 'brands' => $brands
        ]);
    }
}