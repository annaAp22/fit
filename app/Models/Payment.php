<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'descr',
        'add',
        'status'
    ];



    public function orders() {
        return $this->hasMany('App\Models\Order', 'payment_id');
    }
}
