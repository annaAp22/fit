<?php

use Illuminate\Database\Seeder;
use App\Models\OrderStatuses;

class OrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        OrderStatuses::firstOrCreate([
            'sysname' => 'wait',
            'name' => 'В ожидании',
        ]);
        OrderStatuses::firstOrCreate([
            'sysname' => 'work',
            'name' => 'В работе',
        ]);
        OrderStatuses::firstOrCreate([
            'sysname' => 'send',
            'name' => 'Отправлен',
        ]);
        OrderStatuses::firstOrCreate([
            'sysname' => 'cancel',
            'name' => 'Отменен',
        ]);
        OrderStatuses::firstOrCreate([
            'sysname' => 'complete',
            'name' => 'Выполнен',
        ]);
    }
}
