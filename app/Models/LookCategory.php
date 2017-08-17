<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LookCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'sort',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function looks()
    {
        return $this->hasMany('App\Models\Look', 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSort($query)
    {
        return $query->orderBy('sort', 'ASC');
    }
}
