<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;
use Illuminate\Database\Eloquent\SoftDeletes;

class Look extends Model
{
    use LaravelUploads,
        SoftDeletes;

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'image',
        'status',
        'category_id',
    ];

    protected $uploads = [
        'image' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '100x100@60',
            'normal'    => '866x592@90',
        ],
    ];

    // Relations
    public function products()
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('position');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\LookCategory', 'category_id');
    }
    /*
     * @return false, если у товаров нет общих размеров иначе true.
     * в сравнении учавствуют только те товары, у которых есть размеры
     * **/
    public function hasCommonSizes() {
        $arr = [];
        $count = 0;
        foreach($this->products as $product) {
            $sizes = $product->sizes;
            if(count($sizes)) {
                $count++;
                foreach ($sizes as $size) {
                    if(!isset($arr[$size])) {
                        $arr[$size] = 1;
                    }else {
                        $arr[$size]++;
                    }
                }
            }
        }
        foreach ($arr as $val) {
            if($val === $count) {
                return true;
            }
        }
        return false;
    }
    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

}
