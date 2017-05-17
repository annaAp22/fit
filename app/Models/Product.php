<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

        'video_url',

        'price',
        'discount',
        'sku',

        'new',
        'act',
        'hit',
        
        'stock',
        'status',
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '45x67',
            'modal' => '93x140',
            'listing'       => '179x269',
            'small'      => '400x600',
            'main'  => '1240x1860',
            'cart' => '65x98'
        ],
    ];



    public function getSlideClassAttribute() {
        $slideClass = '';

        if($this->hit) $slideClass .= 'mod-hit ';
        if($this->act) $slideClass .= 'mod-sale ';
        if($this->new) $slideClass .= 'mod-new ';

        return $slideClass;
    }
    public function getComments($count, $skip = 0) {
        return $this->hasMany('App\Models\ProductComment', 'product_id')->skip($skip)->take($count)->get();
    }

    public function photos() {
        return $this->hasMany('App\Models\ProductPhoto', 'product_id');
    }
    public function commentsCount() {
        return $this->hasMany('App\Models\ProductComment', 'product_id')->count();
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



    public function scopePublished($query) { return $query->where('status', 1); }
    public function scopeHit($query) { return $query->where('hit', 1); }
    public function scopeAct($query) { return $query->where('act', 1); }
    public function scopeSale($query) { return $this->scopeAct($query); }
    public function scopeNew($query) { return $query->where('new', 1); }
    public function scopeRecentlyAdded($query) { return $query->orderBy('created_at', 'desc'); }




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
        if(!$this->kits->count()) return false;

        $kit = $this->kits->first();
        
        return $kit->products->where('id', '!=', $this->id);
    }
}
