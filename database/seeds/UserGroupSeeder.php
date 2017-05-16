<?php

use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\UserGroup::firstOrCreate([
            'name'     => 'admin',
            'name_rus' => 'Администратор',
            'descr'    => 'Группа администраторов, с полными правами'
        ]);

        App\Models\UserGroup::firstOrCreate([
            'name'     => 'moderator',
            'name_rus' => 'Модератор',
            'descr'    => 'Группа модераторов, с ограниченным кол-вом прав'
        ]);

        App\Models\UserGroup::firstOrCreate([
            'name'    => 'manager',
            'name_rus'=> 'Менеджер',
            'descr'   => 'Группа менеджеров, с правами для обработки заказов'
        ]);

        App\Models\UserGroup::firstOrCreate([
            'name'    => 'content-manager',
            'name_rus'=> 'Контент-менеджер',
            'descr'   => 'Группа контент-менеджеров, с правами на управление контентом'
        ]);

        App\Models\UserGroup::firstOrCreate([
            'name'    => 'customer',
            'name_rus'=> 'Покупатель',
            'descr'   => 'Группа покупателей с доступом к ЛК, без доступа к панели управления'
        ]);
    }
}
