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
        'sysname',
        'descr',
        'price',
        'status'
    ];

    public function orders() {
        return $this->hasMany('App\Models\Order', 'delivery_id');
    }
    //
    public function payments() {
        return $this->belongsToMany('App\Models\Payment');
    }
    //scopes
    /*
     * @return published records
     * **/
    public function scopePublished($query) { return $query->where('status', 1); }
    //accessors
    public function getPaymentsListAttribute() {
        return implode(',', $this->payments->pluck('id')->toArray());
    }
}
