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
            ->withTimestamps();
    }
    public function getSizeByProduct($product) {
      if(isset($product->pivot->extra_params)) {
        $arr = json_decode($product->pivot->extra_params);
      }else {
        return 0;
      }

      if(isset($arr->size)) {
        return $arr->size;
      }else {
        return 0;
      }
    }
    public function getTotalWithDeliveryAttribute() {
        $delivery_price = isset($this->delivery) ? $this->delivery->price : 0;
        $total = $this->attributes['amount'] + $delivery_price;
        return $total;
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
