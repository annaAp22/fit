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
        'sysname',
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

    // TODO: Move to database if change ability needed
    public $colors = [
        '#5B0F00' => 'Бордовый',
        '#660000' => 'Бордовый',
        '#783F04' => 'Коричневый',
        '#7F6000' => 'Салатовый',
        '#274E13' => 'Зеленый',
        '#0C343D' => 'Синий',
        '#1C4587' => 'Синий',
        '#073763' => 'Синий',
        '#20124D' => 'Синий',
        '#4C1130' => 'Розовый',
        '#85200C' => 'Коричневый',
        '#990000' => 'Бордовый',
        '#B45F06' => 'Оранжевый',
        '#BF9000' => 'Желтый',
        '#38761D' => 'Зеленый',
        '#134F5C' => 'Бирюзовый',
        '#1155CC' => 'Синий',
        '#0B5394' => 'Синий',
        '#351C75' => 'Синий',
        '#741B47' => 'Розовый',
        '#A61C00' => 'Бордовый',
        '#CC0000' => 'Красный',
        '#E69138' => 'Оранжевый',
        '#F1C232' => 'Желтый',
        '#6AA84F' => 'Мятный',
        '#45818E' => 'Бирюзовый',
        '#3C78D8' => 'Синий',
        '#3D85C6' => 'Голубой',
        '#674EA7' => 'Фиолетовый',
        '#A64D79' => 'Розовый',
        '#CC4125' => 'Красный',
        '#E06666' => 'Розовый',
        '#F6B26B' => 'Оранжевый',
        '#FFD966' => 'Желтый',
        '#93C47D' => 'Зеленый',
        '#76A5AF' => 'Бирюзовый',
        '#6D9EEB' => 'Бирюзовый',
        '#6FA8DC' => 'Бирюзовый',
        '#8E7CC3' => 'Фиолетовый',
        '#C27BA0' => 'Розовый',
        '#DD7E6B' => 'Оранжевый',
        '#EA9999' => 'Розовый',
        '#F9CB9C' => 'Розовый',
        '#FFE599' => 'Желтый',
        '#B6D7A8' => 'Зеленый',
        '#A2C4C9' => 'Голубой',
        '#A4C2F4' => 'Голубой',
        '#9FC5E8' => 'Голубой',
        '#B4A7D6' => 'Фиолетовый',
        '#D5A6BD' => 'Розовый',
        '#E6B8AF' => 'Розовый',
        '#F4CCCC' => 'Розовый',
        '#FCE5CD' => 'Розовый',
        '#FFF2CC' => 'Розовый',
        '#D9EAD3' => 'Зеленый',
        '#D0E0E3' => 'Голубой',
        '#C9DAF8' => 'Синий',
        '#CFE2F3' => 'Голубой',
        '#D9D2E9' => 'Голубой',
        '#EAD1DC' => 'Розовый',
        '#980000' => 'Бордовый',
        '#FF0000' => 'Красный',
        '#FF9900' => 'Оранжевый',
        '#FFFF00' => 'Желтый',
        '#00FF00' => 'Салатовый',
        '#00FFFF' => 'Бирюзовый',
        '#4A86E8' => 'Синий',
        '#0000FF' => 'Синий',
        '#9900FF' => 'Фиолетовый',
        '#FF00FF' => 'Розовый',
        '#000000' => 'Черный',
        '#222222' => 'Серый',
        '#444444' => 'Серый',
        '#666666' => 'Серый',
        '#888888' => 'Серый',
        '#AAAAAA' => 'Серый',
        '#CCCCCC' => 'Серый',
        '#DDDDDD' => 'Серый',
        '#EEEEEE' => 'Серый',
        '#FFFFFF' => 'Белый',
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

    // Get color name by its hash
    public function getColorAttribute($code = null)
    {
        if( !$code )
        {
            if( $this->attributes['type'] != 'color' )
                return null;
            $code = $this->pivot->value;
        }

        return isset($this->colors[$code]) ? $this->colors[$code] : null;
    }
}
