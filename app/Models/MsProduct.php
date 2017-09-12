<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsProduct extends Model
{
    protected $dates = ['created_at, updated_at'];
    protected $fillable = [
        'parent',
        'product_id',
        'ms_uuid',
        'ms_sku',
        'size',
        'ms_type',
        'ms_externalCode',
        'ms_quantity',
        'ms_salePrice',
        'ms_weight',
        'ms_volume',
        'ms_buyPrice',
        'created_at',
        'updated_at'
    ];

    //related product
    public function product()
    {
        return $this->belongsTo("App\Models\Product", "product_id");
    }
}
