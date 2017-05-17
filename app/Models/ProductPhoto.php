<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;

class ProductPhoto extends Model
{
    use LaravelUploads;
    
    protected $table = 'product_photos';

    protected $fillable = ['product_id', 'img' ];

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

    public static $rules = ['img' => 'image'];
}
