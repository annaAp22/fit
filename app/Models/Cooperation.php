<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooperation extends Model
{
    //
    protected $guarded = ['id', 'type'];
    //
    public function scopeNotNotified($query) {
        $query->where('notify', 0);
    }
}
