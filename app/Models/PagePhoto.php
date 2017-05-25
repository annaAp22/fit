<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;

class PagePhoto extends Model
{
	use LaravelUploads;

    protected $table = 'page_photos';

    protected $fillable = [ 'page_id', 'name', /* 'img' */ ];

    public static $rules = ['img' => 'image'];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '508x400@100',
        ],
    ];
}
