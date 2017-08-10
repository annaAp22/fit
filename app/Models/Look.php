<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Look extends Model
{
    protected $casts = [
        'dot' => 'object'
    ];

    protected $fillable = [
        'name',
        'image',
        'dot',
        'status',
    ];
}
