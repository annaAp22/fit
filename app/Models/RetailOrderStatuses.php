<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailOrderStatuses extends Model
{
    //
    protected $fillable = [
        'sysname',
        'name',
        'status_id',
    ];
    /**
    * @return статус заказа, который соответствует статусу из crm
     */
    public function status() {
        return $this->belongsTo('App\Models\OrderStatuses', 'status_id');
    }

}
