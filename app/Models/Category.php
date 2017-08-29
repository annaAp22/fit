<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Indiesoft\LaravelUploads\LaravelUploads;

class Category extends Model
{
    use SoftDeletes, LaravelUploads;
    private $_categoryType;
    private $recursionCount;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'parent_id',
        'sysname',

        'name',
        'text',
        'text_preview',

        'title',
        'description',
        'keywords',

        'status',

        'new',
        'act',
        'hit',
        'sort',
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '145x180',
        ],
        'img_main' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '559x376',
        ],
        'icon' => [
            'extensions' => 'jpg,jpeg,png',
        ],
    ];
    /*
     *@return системное имя корневой категории
     * **/
    public function getRootCategorySysname($category = null, $count = 0) {
        if($count > 5) {
            return 'woman';
        }
        if(!$category) {
            $category = $this;
        }
        if($category->parent_id == 0) {
            $categoryType =  $category->sysname;
        } else {
            $category = self::where('id', $category->parent_id)->first();
            $categoryType = $this->getRootCategorySysname($category, $count + 1);
        }
        return $categoryType;
    }
    /*
     * scopes
     * **/
    public function scopeRoots($query)
    {
        return $query->where(function($query) {
                            $query->where('parent_id', 0)
                                ->orWhere('parent_id', null);
                        });
    }
    public function scopePublished($query) { return $query->where('status', 1); }
    public function scopeSorted($query) { return $query->orderBy('sort'); }
    public function scopeSysname($query, $sysname) { return $query->where('sysname', $sysname); }
    public function scopeChildrenOf($query, Category $category) { return $query->where('parent_id', $category->id); }


    public function children() { return $this->hasMany('App\Models\Category', 'parent_id'); }
    public function parent() { return $this->belongsTo('App\Models\Category', 'parent_id'); }

    public function products() {
        return $this
            ->belongsToMany('App\Models\Product',
                'category_product',
                'category_id',
                'product_id')
            ->withPivot('sort')
            ->orderBy('category_product.sort');
    }

    // Offer banners
    public function offers()
    {
        return $this->belongsToMany('App\Models\Offer');
    }

    // Get all descendants products
    public function children_rec()
    {
        return $this->children()->with('children_rec');
    }

    // Get all children categories ids
    public function children_ids($category, $ids) {
        $ids = $ids->merge([$category->id]);
        foreach($category->children_rec as $child)
        {
            $ids = $ids->merge([$child->id]);
            if($child->hasChildren)
            {
                $ids = $this->children_ids($child, $ids);
            }
        }
        return $ids->unique();
    }
    // Get all products for children
    public function products_rec($category, $products)
    {
        $products = $products->merge($category->products);
        foreach($category->children_rec as $child)
        {
            $products = $products->merge($child->products);
            if($child->hasChildren)
            {
                $products = $this->products_rec($child, $products);
            }
        }
        return $products->unique('id');
    }

    public function productsWithoutSort() {
        return $this
            ->belongsToMany('App\Models\Product',
                'category_product',
                'category_id',
                'product_id')
            ->withPivot('sort');
    }



    public function getFiltersAttribute() {
        $filters = [];

        foreach($this->products as $product)
            if(isset($product->relations['attributes']))
                foreach($product->relations['attributes'] as $attr) {
                    if(in_array($attr->original['type'], ['checklist']))
                        $attr->value = json_decode($attr->original['pivot_value']);
                    else
                        $attr->value = $attr->original['pivot_value'];
                    if(isset($filters[$attr->id])) {
                        $filters[$attr->id]->values = $filters[$attr->id]->values->merge($attr->value)->unique()->sort();
                        continue;
                    }

                    $filters[$attr->id] = $attr;
                    $filters[$attr->id]->values = collect($attr->value);
                }

        return collect($filters);
    }

    public function getHasChildrenAttribute() {
        return $this->children->count() > 0;
    }

    public function getProductsCountAttribute() {
        if(!$this->hasChildren) return $this->products->count();

        $count = 0;
        foreach($this->children as $cat)
            $count += $cat->productsCount;

        return $count;
    }

    public function getMinPriceAttribute() {

        if(!$this->hasChildren)
            return $this->products->min('price');

        $min = PHP_INT_MAX;
        foreach($this->children as $cat) {
            $catMinPrice = $cat->products->min('price');
            if($catMinPrice < $min) $min = $catMinPrice;
        }

        return ($min == PHP_INT_MAX) ? false : $min;
    }

    // Set zero if parent is not selected
    public function setParentIdAttribute($value) {
        $this->attributes['parent_id'] = $value ? $value : 0;
    }
}
