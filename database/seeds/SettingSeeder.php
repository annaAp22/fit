<?php

use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::firstOrCreate([
            'type'  => 'string',
            'var'   => 'perpage',
            'value' => 9
        ]);

        Setting::firstOrCreate([
            'type'  => 'string',
            'var'   => 'phone_number',
            'value' => '+7 (495) <span class="mod-bold">123-45-67</span>'
        ]);

        Setting::firstOrCreate([
            'type'  => 'string',
            'var'   => 'email_support',
            'value' => 'support@somesite.ru'
        ]);

        Setting::firstOrCreate([
            'type'  => 'string',
            'var'   => 'email_order',
            'value' => 'order@somesite.ru'
        ]);

        Setting::firstOrCreate([
            'type'  => 'string',
            'var'   => 'address',
            'value' => 'г. Москва, ул. Пушкина, д. Колотушкина',
        ]);

        Setting::firstOrCreate([
            'type'  => 'array',
            'var'   => 'schedule',
            'value' => '{"start_workday":"9:00","end_workday":"19:00","start_weekend":"10:00","end_weekend":"17:00"}'
        ]);
    }
}
