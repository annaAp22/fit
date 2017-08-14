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
      $settings = [
          [
              'var' => 'perpage',
              'type' => 'string',
              'value' => '9',
          ],
          [
              'var' => 'phone_number',
              'type' => 'string',
              'value' => '+7 (495) <span class="mod-bold">123-45-67</span>',
          ],
          [
              'var' => 'email_support',
              'type' => 'string',
              'value' => 'support@somesite.ru',
          ],
          [
              'var' => 'email_order',
              'type' => 'string',
              'value' => 'order@somesite.ru',
          ],
          [
              'var' => 'address',
              'type' => 'string',
              'value' => 'г. Москва, ул. Пушкина, д. Колотушкина',
          ],
          [
              'var' => 'schedule',
              'type' => 'array',
              'value' => '{"start_workday":"9:00","end_workday":"19:00","start_weekend":"10:00","end_weekend":"17:00"}',
          ],
          [
              'var' => 'shop_name',
              'type' => 'string',
              'value' => 'Интернет-магазин «Fit2U»',
          ],
          [
              'var' => 'company_name',
              'type' => 'string',
              'value' => 'Твой Фитнес имидж',
          ],
          [
              'var' => 'retailcrm_url',
              'type' => 'string',
              'value' => 'https://fit2u.retailcrm.ru',
          ],
          [
              'var' => 'top_menu_count_in_row',
              'type' => 'string',
              'value' => '8',
          ],
      ];
      //усли значение не задано то устанавливаем, а если задано, то уже кто-то настроил
      foreach ($settings as $setting) {
        $row = Setting::firstOrNew([
            'var'   => $setting['var'],
        ]);
        if(!isset($row->value) or !isset($row->type)) {
          $row->type = $setting['type'];
          $row->value = $setting['value'];
          $row->save();
        }
      }
    }
}
