<?php

namespace App\Http\Controllers;

use App\Library\MoySklad\Ms;
use App\Models\Attribute;
use App\Models\MsProduct;
use Illuminate\Http\Request;

class RunOnceController extends Controller
{
  /**
   * Добавляем аттрибут всех размеров в таблицу, берутся размеры болеше 30 и символьные.
   * Обновляем размеры товаров, которые есть в наличии.
   * В итоге оба аттрибута будут содержать одинаковые значения размеров
   * */
  public function addMsSizesAttribute() {
    $all_sizes_attr_name = 'Все размеры';
    $products = MsProduct::select('ms_sku')->get();
    $sizes = array();
    $keys = array();
    foreach ($products as $product) {
      if(strpos($product->ms_sku, "-")) {
        $size = last(explode("-", $product->ms_sku));
        if(!isset($keys[$size]) and $size > '30') {
          $keys[$size] = 1;
          $sizes[] = $size;
        }
      }
    }
    sort($sizes);
    $sizes_arr = [
        'name' => $all_sizes_attr_name,
        'type' => 'checklist',
        'is_filter' => 0,
        'status' => 1,
        'unit' => '',
        'list' => json_encode($sizes),
    ];
    $sizesAttr = Attribute::where('name','=', $all_sizes_attr_name)->first();
    if(!$sizesAttr) {
      Attribute::create($sizes_arr);
    }else {
      $sizesAttr->update(['list' => $sizes_arr['list']]);
    }
    $sizesAttr = Attribute::where('name','=', 'Размеры')->first()->update(['list' => $sizes_arr['list']]);
    var_dump(json_encode($sizes));
    return 'аттрибут с заданными размерами добавлен';
  }
  public function updatePriceAndStock() {
    $ms = new MoySkladController();
    echo $ms->updatePriceAndStock(new Ms());
  }
  /*
   *
   * **/
  public function removeSizesType() {
    $q = Attribute::where('name', 'Тип размера');
    $attribute = $q->first();
    if($attribute) {
      $attribute->products()->detach();
      $q->forceDelete();
      return 'типы размеров удалены';
    }
    return 'типы размеров не найдены';
  }
  /*
   * удаление размеров связанных с полом
   * **/
  public function removeSizesSex() {
    $sex = array('Женские размеры', 'Мужские размеры');
    $result = '';
    foreach ($sex as $name) {
      $q = Attribute::where('name', $name);
      $attribute = $q->first();
      if($attribute) {
        $attribute->products()->detach();
        $q->forceDelete();
        $result .=  $name." удалены.\n";
      }
    }
    if($result) {
      return $result;
    }else
      return 'нечего удалять';
  }
}
