<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Category;

class ListingCatalog extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'current' => null,
        'parent_id' => 2
    ];

    public function __construct(array $config = [])
    {
        $this->addConfigDefaults([
            'current' => (object)['id' => 2, 'parent_id' => 0],
        ]);

        parent::__construct($config);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $category = Category::with([
            'children' => function($query) {
                $query->published()->sorted();
            },
            'children.children' => function($query) {
                $query->published()->sorted();
            }
        ])
            ->roots()
            ->where('id', $this->config['parent_id'])
            ->published()
            ->sorted()
            ->first();

        return view("widgets.listing_catalog", [
            'config' => $this->config,
            'category' => $category,
        ]);
    }
}