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
    if(!Attribute::where('name', 'Размеры')->first())
      Attribute::firstOrCreate([
          'type'      => 'checklist',
          'name'      => 'Размеры',
          'unit'      => '',
          'list'      => json_encode($sizes),
          'is_filter' => true,
          'status'    => 1,
      ]);
    $sizes = [];
    for($i = 38; $i <= 48; $i += 2) $sizes[] = $i;
    if(!Attribute::where('name', 'Женские размеры')->first())
      Attribute::firstOrCreate([
          'type'      => 'checklist',
          'name'      => 'Женские размеры',
          'unit'      => '',
          'list'      => json_encode($sizes),
          'is_filter' => false,
          'status'    => 1,
      ]);
    $sizes = [];
    for($i = 46; $i <= 54; $i += 2) $sizes[] = $i;
    if(!Attribute::where('name', 'Мужские размеры')->first())
      Attribute::firstOrCreate([
          'type'      => 'checklist',
          'name'      => 'Мужские размеры',
          'unit'      => '',
          'list'      => json_encode($sizes),
          'is_filter' => false,
          'status'    => 1,
      ]);
    $list = array(
        'Не определен',
        'Женский',
        'Мужской',
    );
    if(!Attribute::where('name', 'Тип размера')->first())
      Attribute::firstOrCreate([
          'type'      => 'list',
          'name'      => 'Тип размера',
          'unit'      => '',
          'list'      => json_encode($list),
          'is_filter' => false,
          'status'    => 1,
      ]);

  }
}
