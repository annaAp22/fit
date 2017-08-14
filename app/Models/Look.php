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
}