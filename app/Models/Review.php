<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'status',
    ];



    public function scopePublished($query) { $query->where('status', 1); }
    public function scopeRecent($query) { $query->orderBy('created_at', 'desc'); }
}
