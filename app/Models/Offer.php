<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Indiesoft\LaravelUploads\LaravelUploads;

class Offer extends Model
{
    use SoftDeletes, LaravelUploads;

    protected $fillable = [
        'name',
        'image',
        'url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected $uploads = [
        'image' => [
            'extensions' => 'jpeg,jpg,png',
            'preview'    => '231x45@80',
            'lg'         => '1155x226@80',
        ]
    ];

    // Relations
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }
}
