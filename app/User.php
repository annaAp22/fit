<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function group() {
        return $this->belongsTo('App\Models\UserGroup', 'group_id');
    }

    public function orders() {
        return $this->hasMany('App\Models\Order', 'customer_id');
    }

    public function getPurchasedProductsAttribute() {
        $result = collect();
        $orders = $this->orders()->with('products')->get();

        foreach($orders as $order)
            $result->merge($order->products);

        return $result;
    }
}
