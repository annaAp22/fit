<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsProduct extends Model
{
    protected $dates = ['created_at, updated_at'];
    protected $fillable = [
        'product_id',
        'ms_uuid',
        'ms_sku',
        'size',
        'ms_type',
        'ms_externalCode',
        'ms_quantity',
        'ms_salePrice',
        'created_at',
        'updated_at'
    ];

    //related product
    public function product()
    {
        return $this->belongsTo("App\Models\Product", "product_id");
    }
}
