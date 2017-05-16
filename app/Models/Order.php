<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Date;

class Order extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    static public $statuses = [
        'wait' => 'В ожидании',
        'work' => 'В работе',
        'send' => 'Отправлен',
        'cancel' => 'Отменен',
        'complete' => 'Завершен' 
    ];

    protected $fillable = [
        'delivery_id',
        'payment_id',

        'datetime',
        
        'name',
        'email',
        'phone',
        'descr',
        'address',
        'payment_add',
        'amount',

        'status'
    ];



    public function statusName() {
        return self::$statuses[$this->status];
    }

    public function dateLocale() {
        return (new Date($this->datetime))->format('d F Y H:i');
    }

    public function datePicker() {
        return (new Date($this->datetime))->format('d.m.Y H:i');
    }



    public function products() {
        return $this
            ->belongsToMany('App\Models\Product',
                'order_product',
                'order_id',
                'product_id')
            ->withPivot('cnt', 'price', 'extra_params')
            ->withTimestamps();;
    }

    public function payment() {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }

    public function delivery() {
        return $this->belongsTo('App\Models\Delivery', 'delivery_id');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'customer_id');
    }
}
