<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kit extends Model
{
	protected $fillable = ['name'];
	
    public function products() {
    	return $this->belongsToMany('App\Models\Product',
    		'kit_product',
    		'kit_id',
    		'product_id');
    }
}
