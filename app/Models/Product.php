<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Indiesoft\LaravelUploads\LaravelUploads;
use Validator;

class Product extends Model
{
    use SoftDeletes, LaravelUploads;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'category_id',
        'brand_id',
        'sysname',

        'name',
        'descr',
        'text',

        'title',
        'description',
        'keywords',
        'img',
        'video_url',

        'price',
        'discount',
        'sku',

        'new',
        'new_sort',
        'act',
        'act_sort',
        'hit',
        'hit_sort',

        'ya_market',
        'merchant',

        'stock',
        'status',
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '45x67',
            'thumb'      => '96x144',
            'cart'       => '81x122',
            'modal'      => '120x180',
            'listing'    => '360x540',
            'kit'        => '330x495',
            'detail'     => '408x613',
            'big'        => '1240x1860'
        ],
    ];
    private $_availableSizes = null;
    private $_sizes = null;
    /*
     * @return array of sizes which need to show
     * @param $sizesData contain arrays of sizes manSizes and womanSizes
     * **/
    public function getSizesAttribute() {
        if(isset($this->_sizes)) {
            return $this->_sizes;
        }
        $sizeAttr = $this->relations['attributes']->where('name', 'Все размеры')->first();
        if($sizeAttr && isset($sizeAttr->pivot->value)) {
            $sizes = json_decode($sizeAttr->pivot->value);
        }else {
            $sizes = $this->availableSizes;
        }
        return $sizes;
    }
    /*
     * @return array of available sizes
     * **/
    public function getAvailableSizesAttribute() {
        if($this->_availableSizes)
            return $this->_availableSizes;
        $sizeAttr = $this->relations['attributes']->where('name', 'Размеры')->first();
        if($sizeAttr && isset($sizeAttr->pivot->value)) {
            $sizes = json_decode($sizeAttr->pivot->value);
        }else {
            $sizes = [];
        }
        $this->_availableSizes = $sizes;
        return $sizes;
    }
    /*
     * @return gender by product
     * **/
    public function getSex() {
        $attr = $this->relations['attributes']->where('name', 'Пол')->first();
        if($attr && isset($attr->pivot->value)) {
            return $attr->pivot->value;
        } else {
            return null;
        }
    }
    /*
     * wrap the article in a name by tag span
     * **/
    public function getWrapTagInName() {
        if(!$this->sku)
            return $this->name;
        return str_replace($this->sku, '<span>'.$this->sku.'</span>', $this->name);
    }
    /**accessors*/
    public function getSlideClassAttribute() {
        $slideClass = '';

        if($this->hit) $slideClass .= 'mod-hit ';
        if($this->act) $slideClass .= 'mod-sale ';
        if($this->new) $slideClass .= 'mod-new ';

        return $slideClass;
    }
    public function photos() {
        return $this->hasMany('App\Models\ProductPhoto', 'product_id');
    }

    public function comments() {
        return $this->hasMany('App\Models\ProductComment', 'product_id');
    }

    public function avgRating() {
        return $this->comments()
            ->selectRaw('avg(rating) as aggregate, product_id')
            ->groupBy('product_id');
    }

    public function categories() {
        return $this
            ->belongsToMany('App\Models\Category',
                'category_product',
                'product_id',
                'category_id')
            ->withPivot('sort');
    }

    public function kits() {
        return $this->belongsToMany('App\Models\Kit',
            'kit_product',
            'product_id',
            'kit_id');
    }

    public function brand() {
        return $this->belongsTo('App\Models\Brand', 'brand_id');
    }

    public function tags()
    {
        return $this
            ->belongsToMany('App\Models\Tag',
                'product_tag',
                'product_id',
                'tag_id')
            ->withTimestamps();
    }

    public function related()
    {
        return $this
            ->belongsToMany('App\Models\Product',
                'product_related',
                'product_id',
                'product_id2')
            ->withTimestamps();
    }

    public function attributes() {
        return $this
            ->belongsToMany('App\Models\Attribute',
                'attribute_product',
                'product_id',
                'attribute_id')
            ->withPivot('value');
    }

    public function ms_products()
    {
        return $this->hasMany('App\Models\MsProduct');
    }

    // Look books
    public function looks()
    {
        return $this->belongsToMany('App\Models\Look')->withPivot('position');
    }

    public function scopePublished($query) { return $query->where('status', 1); }
    public function scopeHit($query) { return $query->where('hit', 1); }
    public function scopeAct($query) { return $query->where('act', 1); }
    public function scopeSale($query) { return $this->scopeAct($query); }
    public function scopeNew($query) { return $query->where('new', 1); }
    public function scopeRecentlyAdded($query) { return $query->orderBy('created_at', 'desc'); }
    /*
     * возвращает товары, без повторов, для заданной страницы
     * дописывает в коллекцию Общее число товаров - totalCount и общее число страниц - totalPages.
     * использует стандартный paginate, поэтому доступны методы, для получения информации о странице
     * **/
    public function scopeDistinctPaginate($query, $perpage) {
        $field = $this->getTable().'.id';
        //$products = $query->distinct($field)->published()->paginate($perpage);
        $products = $query->published()->paginate($perpage);
        $products->totalCount = $query->count($field);
        $products->totalPages = intval(($products->totalCount + $perpage - 1) / $perpage);
        foreach($products as $product) {
            $category = $product->categories[0];
            if(isset($category->pivot->sort)) {
                $product->sort = $category->pivot->sort;
            }
        }
        return $products;
    }
    public function scopeWithInfo($query) {
        $query->with([
            'attributes',
            'comments' => function($query){
                $query->average();
            },
            'categories',
        ]);
    }
    /*
     * получаем запрос товаров для заданной категории вместе с комментариями
     * **/
    public function scopeInCategory($query, $category) {
        $category_ids = $category->hasChildren ? $category->children_ids($category, collect([])) : $category->id;
        $query->select('products.*', 't.sort as sort')
            ->distinct()
            ->whereHas('categories', function($query) use($category_ids) {
                $query->whereIn('categories.id', collect($category_ids));
            })
            ->join(DB::raw('(SELECT product_id, sort FROM `category_product` GROUP BY product_id) t'), function($query) {
                $query->on('products.id', '=', 't.product_id');
            } )
            ->withInfo();
//        $query->join('category_product', 'products.id','category_product.product_id')->select('products.*')
//            ->whereIn('category_product.category_id', collect($category_ids))
//            ->withInfo();
    }
    /*
     * получаем запрос товаров для заданной категории вместе с комментариями
     * **/
    public function scopeInTag($query, $tag) {
        $query->join('product_tag', 'products.id','product_tag.product_id')->select('products.*','sort')
            ->where('tag_id', $tag->id)
            ->withInfo();
    }

    /**
     * Отложил ли покупатель этот товар?
     * @return bool
     */
    public function getIsDeferredAttribute() {
        return session()->has('products.defer.'.$this->id);
    }

    /**
     * Цена без скидки
     * @return bool|float
     */
    public function getOriginalPriceAttribute() {
        if(!$this->price || !$this->discount) {
            return false;
        }
        return ceil($this->price * 100 / (100 - $this->discount));
    }
    public function getAverageRatingAttribute() {
        if (! array_key_exists('avgRating', $this->relations))
            $this->load('avgRating');

        $relation = $this->getRelation('avgRating')->first();

        return ($relation) ? $relation->aggregate : 0;
    }

    /**
     * Устанавливает связь с атрибутами и значениями для товара из запроса.
     * @return bool
     */
    public function setAttributesFromRequest($sync = false) {
        if(!Request::has('attributes')) return false;
        $allSaved = true;

        $attributes = [];
        foreach(Request::input('attributes') as $attr_id => $attr_value) {
            if(is_array($attr_value)) $attr_value = json_encode($attr_value);

            $validator = Validator::make([
                'id'    => $attr_id,
                'value' => $attr_value
            ], [
                'id'    => 'required|exists:attributes,id',
                'value' => 'required'
            ]);

            if($validator->passes()) $attributes[$attr_id] = ['value' => $attr_value];
            else $allSaved = false;
        }

        if(!empty($attributes))
            if($sync) $this->attributes()->sync($attributes);
            else
                foreach($attributes as $attr_id => $attr_value)
                    $this->attributes()->attach($attr_id, $attr_value);
        return $allSaved;
    }

    public function getRestKitAttribute() {

        // If related not empty (new kit) return related products else old kit products
        if($this->related()->count())
        {
            return $this->related;
        }

        if(!$this->kits->count()) return false;

        $kit = $this->kits->first();

        return $kit->products->where('id', '!=', $this->id);
    }

    public function getPositionAttribute()
    {
        $position = json_decode($this->pivot->position);
        return $position;
    }
}
