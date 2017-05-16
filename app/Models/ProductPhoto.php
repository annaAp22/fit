<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;

class ProductPhoto extends Model
{
    use LaravelUploads;
    
    protected $table = 'product_photos';

    protected $fillable = ['product_id', /* 'img' */];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '45x67',
            'modal' => '93x140',
            'listing'       => '179x269',
            'small'      => '400x600',
            'main'  => '1240x1860',
            'cart' => '65x98',
        ],
    ];

    public static $rules = ['img' => 'image'];
}
