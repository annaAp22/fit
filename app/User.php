<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'group_id', 'status', 'birthday', 'subscription'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $dates = ['birthday'];
    //
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
    /*
     * send mail on reset password
     * **/
    public function sendPasswordResetNotification($token) {
        Mail::send('auth.emails.password',
            [
                'token' => $token,
                'user' => $this,
            ],
            function ($message) {
                $caption = 'Сброс пароля на сайте fit2u';
                $message->to($this->email)->subject($caption);
            });
    }
    //accessors
    //scopes
    public function scopePublished($query) {
        $query->where('status', 1);
    }

}