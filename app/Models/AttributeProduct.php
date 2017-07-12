<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    //
    protected $table = 'attribute_product';
    /*
     * @param $name - имя аттрибута
     * @param $value - значение аттрибута
     * **/
    public function getProductsByAttribute($name, $value) {
        $attribute = Attribute::where('name', $name)->published()->first();
        if($attribute) {
            $attributes = $this->select('product_id')->where('attribute_id', $attribute->id)->where('value', $value)->get();
            $ids = array();
            foreach ($attributes as $item) {
                $ids[] = $item->product_id;
            }
            return $ids;
        }
        return array();
    }

}
