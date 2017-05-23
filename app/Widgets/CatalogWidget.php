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
        'marker_down' => false,
        'type' => null,
    ];
    /**
    */
    protected $categories;
    protected function init() {
        $this->categories = Category::with([
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
    }
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if(!$this->categories) {
            $this->init();
        }
        if($this->config['type'] == 'footerMenu'){
            return $this->footerMenu();
        }else
            return  $this->headerMenu();
    }
    private function headerMenu() {
        return view("widgets.catalog_widget", [
            'config' => $this->config,
            'categories' => $this->categories,
        ]);
    }
    private function footerMenu() {
        return view("widgets.footer_menu", [
            'categories' => $this->categories,
        ]);
    }
}