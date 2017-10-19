<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    //
    protected $guarded = [
        'id'
    ];
    protected $casts = [
        'extra' => 'array',
    ];
    //scopes
    public function scopeLast($query) {
        $query->orderBy('created_at', 'desc');
    }
}