<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;

class News extends Model
{
    use LaravelUploads;

    protected $table = 'news';

    protected $fillable = [
        'sysname',

        'name',
        'body',

        'title',
        'keywords',
        'description',

        'status',
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpg,jpeg,png',
            'preview'    => '265x180@100',
        ],
    ];

    public function scopePublished($query) { $query->where('status', 1); }
    public function scopeRecent($query) { $query->orderBy('created_at', 'desc'); }
    public function scopeBySysname($query, $sysname) { $query->where('sysname', $sysname); }
}
