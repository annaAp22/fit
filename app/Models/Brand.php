<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Indiesoft\LaravelUploads\LaravelUploads;

class Brand extends Model
{
    use SoftDeletes, LaravelUploads;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sysname',

        'name',
        'text',
        //'img',

        'title',
        'description',
        'keywords',

        'status'
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview' => '195x195',
        ],
    ];



    public function products() {
        return $this->hasMany('App\Models\Product', 'brand_id');
    }
}
