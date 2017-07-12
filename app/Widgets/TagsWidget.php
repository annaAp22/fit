<?php

namespace App\Widgets;

use App\Models\Attribute;
use App\Models\AttributeProduct;
use App\Models\Product;
use Arrilot\Widgets\AbstractWidget;
use App\Models\Tag;

class TagsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'category_id' => 0,
        'tag_id' => 0,
        'products' => null,
        'category' => null,
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if($this->config['category']) {
            $category  = $this->config['category'];
        }
        if(isset($category) && $category->parent_id == 0) {
            $attributeProduct = new AttributeProduct();
            if($category->sysname === 'man') {
                $ids = $attributeProduct->getProductsByAttribute('Пол', 'Мужской');
                $tags = Tag::tagsByProductIds($ids)->orderBy('views', 'desc')->orderBy('name')->get();
            }else {
                $ids = $attributeProduct->getProductsByAttribute('Пол', 'Женский');
                $tags = Tag::tagsByProductIds($ids)->orderBy('views', 'desc')->orderBy('name')->get();
            }
        }
        //ищем теги у товаров в этой категории
        elseif($this->config['category_id']) {
            $category_id = $this->config['category_id'];
            $tags = Tag::whereHas('products', function ($query) use ($category_id) {
                $query->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
            })->where('status', 1)->orderBy('views', 'desc')->orderBy('name')->get();
        //ищем тэги у товаров с текущим тэгом)
        } elseif($this->config['tag_id']) {
            $tag_id = $this->config['tag_id'];
            $tags = Tag::where('id', '!=', $tag_id)->whereHas('products', function ($query) use ($tag_id) {
                $query->whereHas('tags', function ($query) use ($tag_id) {
                    $query->where('tag_id', $tag_id);
                });
            })->where('status', 1)->orderBy('views', 'desc')->orderBy('name')->get();
        //по умолчанию все тэги
        } elseif($this->config['products']) {
          $ids = array();
          foreach ($this->config['products'] as $product) {
            $ids[] = $product->id;
          }
          $tags = Tag::tagsByProductIds($ids)->orderBy('views', 'desc')->orderBy('name')->get();
        }else {
            //$tags = Tag::where('status', 1)->orderBy('views', 'desc')->orderBy('name')->get();
            $tags = collect([]);
        }
        return view("widgets.tags_widget", [
            'config' => $this->config, 'tags' => $tags
        ]);
    }
}