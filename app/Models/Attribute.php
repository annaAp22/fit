<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'type',
        'unit',
        'list',
        'is_filter',
        'status'
    ];

    static public $types = [
        'string'    => 'Строковый',
        'integer'   => 'Числовой',
        'list'      => 'Список',
        'color'     => 'Цвет',
        'checklist' => 'Список чекбоксов',
    ];



    public function products() {
        return $this
            ->belongsToMany('App\Models\Product',
                'attribute_product',
                'attribute_id',
                'product_id')
            ->withPivot('value');
    }

    public function getTypeAttribute($value) { return self::$types[$value]; }

    /**
     * Получение значений списка
     * @return mixed|null
     */
    public function getLists() {
        if(in_array($this->type, ['Список', 'Список чекбоксов']) && $this->list) {
            return json_decode($this->list, true);
        }
        return [];
    }
    /*
     * scopes
     * **/
    public function scopePublished($query) {
        return $query->where('status', 1);
    }
}
