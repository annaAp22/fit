<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Indiesoft\LaravelUploads\LaravelUploads;

class Photo extends Model
{
    use LaravelUploads;

    protected $fillable = [
        'img',
        'caption',
        'status',
        'sort',
    ];

    protected $uploads = [
        'img' => [
            'extensions' => 'jpeg,jpg,png',
            'preview' => '100x100@50',
            'detailed' => '360x360@85',
        ]
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 1)
                    ->orderByStatusAndDate();
    }

    public function scopeOrderByStatusAndDate($query)
    {
        return $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
    }
}
