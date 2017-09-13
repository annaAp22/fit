<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email', 'act', 'status'];
    //scopes
    public function scopePublished($query) { $query->where('status', 1); }
}
