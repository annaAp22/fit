<?php

use Illuminate\Database\Seeder;

class MetatagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      $table = DB::table('metatags');
      $table->insert([
          'name' => 'Мой кабинет',
          'route' => 'room',
          'url' => '/room',
          'title' => 'Мой кабинет',
          'description' => 'Мой кабинет',
          'keywords' => 'Мой кабинет',
      ]);
      $table->insert([
          'name' => 'Мои заказы',
          'route' => 'orders-history',
          'url' => '/room/orders',
          'title' => 'Мои заказы',
          'description' => 'Мои заказы',
          'keywords' => 'Мои заказы',
      ]);
    }
}
