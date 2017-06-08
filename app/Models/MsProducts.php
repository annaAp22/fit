<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsProduct extends Model
{
    protected $dates = ['created_at, updated_at'];
    protected $fillable = [
        'ms_uuid',
        'ms_sku',
        'ms_goodsUuid',
    ];

}
