<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;

class Certificate extends Model
{
	use LaravelUploads;
	
    protected $fillable = [/* 'img' */];
    
    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '192x280',
        ],
    ];
}
