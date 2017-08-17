<?php

use Illuminate\Database\Seeder;

use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Attribute::firstOrCreate([
        'type'      => 'color',
        'name'      => 'Основной цвет',
        'unit'      => '',
        'list'      => '',
        'is_filter' => true,
        'status'    => 1
    ]);

    Attribute::firstOrCreate([
        'type'      => 'color',
        'name'      => 'Дополнительный цвет',
        'unit'      => '',
        'list'      => '',
        'is_filter' => true,
        'status'    => 1
    ]);

    $sizes = [];
    for($i = 38; $i <= 50; $i += 2) $sizes[] = $i;
    if(!Attribute::where('name', 'Размеры')->first()) {
        Attribute::firstOrCreate([
            'type'      => 'checklist',
            'name'      => 'Размеры',
            'unit'      => '',
            'list'      => json_encode($sizes),
            'is_filter' => true,
            'status'    => 1,
        ]);
    }
    $attributes = Attribute::get();

    $names = [
        'Страна производства' => 'country',
        'Основной цвет' => 'default_color',
        'Цвет вставок' => 'inserts_color',
        'Размеры' => 'sizes',
        'Назначение' => 'target',
        'Цвет' => 'color',
        'Материал' => 'material',
        'Пол' => 'sex',
        'Все размеры' => 'all_sizes',
        'Дополнительный цвет' => 'additional_color',
    ];
    //записываем системные имена для всех аттрибутов, у которых их нет
    foreach ($attributes as $attribute) {
        if(!isset($attribute->sysname) && isset($names[$attribute->name])) {
            $attribute->sysname = $names[$attribute->name];
            $attribute->save();
        }
    }
  }
}
