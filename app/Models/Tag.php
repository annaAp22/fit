<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function articles()
    {
        return $this->belongsToMany('App\Models\Article', 'article_tag', 'tag_id', 'article_id');
    }

    public function products() {
        return $this
            ->belongsToMany('App\Models\Product',
                'product_tag',
                'tag_id',
                'product_id')
            ->withPivot('sort')
            ->orderBy('sort');
    }
    /*
     * @param ids - array of product id
     * **/
    public function scopeTagsByProductIds($query, $ids) {
      $tags = DB::table('product_tag')->whereIn('product_id', $ids)->get();
      $tagIds = array();
      foreach($tags as $tag) {
        $tagIds[] = $tag->tag_id;
      }
      return $query->published()->whereIn('id', $tagIds);
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
                foreach($product->attributes()->where('is_filter', 1)->where('status', 1)->get() as $attr) {
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
        foreach ($filters as $key => $val) {
          $filters[$key]->values = $val->values->unique();
        }
        return collect($filters);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }
}
