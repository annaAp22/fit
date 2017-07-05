<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    //
  protected $table = 'attribute_product';

  public function scopeAttributeByProducts($query, $products) {
    $productsIds = array();
    foreach ($products as $product) {
      $productsIds[] = $product->id;
    }
    $query->whereIn('product_id', $productsIds);
  }
  public function scopeAttributeByProductsIds($query, $productsIds) {
    $query->whereIn('product_id', $productsIds);
  }

}
