<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Payment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];


    //relations
    public function deliveries() {
        return $this->belongsToMany('App\Models\Delivery');
    }
    public function orders() {
        return $this->hasMany('App\Models\Order', 'payment_id');
    }
    //scopes
    public function scopePublished($query) {
        return $query->where('status', 1);
    }
    //mutators
    public function setSysnameAttribute($value)
    {
        if(!$value) {
            $value = $this->attributes['name'];
        }
        $this->attributes['sysname'] = Str::slug($value);
    }
}
