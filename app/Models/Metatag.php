<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metatag extends Model
{
    protected $fillable = [
        'url',
        'route',

        'name',

        'title',
        'description',
        'keywords'
    ];
}
