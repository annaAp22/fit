<?php

namespace App\Models;

use App\Http\Middleware\Settings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Date;

class Order extends Model
{
    use SoftDeletes;

    protected $dates = ['created_at', 'deleted_at'];
    protected $casts = ['extra_params' => 'array'];

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
        'customer_id',
        'datetime',

        'name',
        'email',
        'phone',
        'descr',
        'address',
        'payment_add',
        'amount',
        'extra_params',
        'status',
    ];
    //скидка в процентах по умолчанию
    private $referral_sale = 10;
    public function statusName() {
        return self::$statuses[$this->status];
    }

    public function dateLocale() {
        return (new Date($this->datetime))->format('d F Y H:i');
    }

    public function datePicker() {
        return (new Date($this->datetime))->format('d.m.Y H:i');
    }
    public function getProducts() {
        return $this->products()->get();
    }
    public function getDiscountAttribute() {
        if(isset($this->extra_params['discount'])) {
            return $this->extra_params['discount'];
        } else {
            return 0;
        }
    }
    public function getDiscountPercentAttribute() {
        if(isset($this->extra_params['discount_percent'])) {
            return $this->extra_params['discount_percent'];
        } else {
            return 0;
        }
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
    /**
    * стоимость заказа с учетом скидки и доставки
    */
    public function getTotalWithDeliveryAttribute() {
        $delivery_price = isset($this->extra_params['delivery_price']) ? $this->extra_params['delivery_price'] : 0;
        $total = $this->attributes['amount'] + $delivery_price - $this->discount;
        return $total;
    }
    public function priceWithDelivery() {
        $s = $this->amount;
        if($this->delivery_id) {
            $s += $this->delivery->price;
        }
        return $s;
    }
    public function payment() {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
    public function getDeliveryPriceAttribute() {
        if(isset($this->extra_params['delivery_price'])) {
            return $this->extra_params['delivery_price'];
        } else {
          return 0;
        }
    }
    public function delivery() {
        return $this->belongsTo('App\Models\Delivery', 'delivery_id');
    }

    public function customer() {
        return $this->belongsTo('App\User', 'customer_id');
    }
}
