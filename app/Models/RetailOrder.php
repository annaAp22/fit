<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailOrder extends Model
{
    //
  protected $fillable = array('order_id');

  public function order() {
    return $this->belongsTo('App\Models\Order');
  }
}
