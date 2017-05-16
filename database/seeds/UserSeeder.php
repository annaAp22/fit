<?php

use Illuminate\Database\Seeder;

use App\Models\UserGroup;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = UserGroup::where('name', 'admin')->first();
        User::firstOrCreate([
            'group_id' => $group->id,
            'name'     => 'Администратор',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('qwerty'),
            'status'   => 1
        ]);

        $group = UserGroup::where('name', 'manager')->first();
        User::firstOrCreate([
            'group_id' => $group->id,
            'name'     => 'Менеджер',
            'email'    => 'manager@admin.com',
            'password' => bcrypt('qwerty'),
            'status'   => 1
        ]);

        $group = UserGroup::where('name', 'content-manager')->first();
        User::firstOrCreate([
            'group_id' => $group->id,
            'name'     => 'Контент-менеджер',
            'email'    => 'cmanager@admin.com',
            'password' => bcrypt('qwerty'),
            'status'   => 1
        ]);

        $group = UserGroup::where('name', 'customer')->first();
        User::firstOrCreate([
            'group_id' => $group->id,
            'name'     => 'Покупатель',
            'email'    => 'testuser@example.com',
            'password' => bcrypt('qwerty'),
            'status'   => 1
        ]);
    }
}
