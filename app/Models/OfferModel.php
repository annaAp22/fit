<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'name',
        'image',
        'url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}
