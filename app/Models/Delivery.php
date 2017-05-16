<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'descr',
        'price',
        'status'
    ];

    public function orders() {
        return $this->hasMany('App\Models\Order', 'delivery_id');
    }
}
