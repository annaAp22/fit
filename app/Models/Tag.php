<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sysname',

        'name',
        'text',

        'title',
        'description',
        'keywords',

        'views',
        'status',
    ];



    public function products() {
        return $this
            ->belongsToMany('App\Models\Product',
                'product_tag',
                'tag_id',
                'product_id')
            ->withPivot('sort')
            ->orderBy('sort');
    }

    public function productsWithoutSort() {
        return $this
            ->belongsToMany('App\Models\Product',
                'product_tag',
                'tag_id',
                'product_id')
            ->withPivot('sort');
    }

    public function getFiltersAttribute() {
        $filters = [];

        foreach($this->products as $product)
                foreach($product->attributes()->get() as $attr) {
                    $attr->value = $attr->pivot->value;
                    if(in_array($attr->original['type'], ['checklist']))
                        $attr->value = json_decode($attr->value);
                    if(isset($filters[$attr->id])) {
                        $filters[$attr->id]->values = $filters[$attr->id]->values->merge($attr->value)->sort();
                        continue;
                    }

                    $filters[$attr->id] = $attr;
                    $filters[$attr->id]->values = collect($attr->value);
                }

        return collect($filters);
    }
}
