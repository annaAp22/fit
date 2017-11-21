<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use AmoCRM\Client;
class AmoCRMController extends Controller
{
    function cooperationOrder(Request $request) {
        return false;
//        $url = 'http://fresh24.bz/siteform/intercept';
//        if( $curl = curl_init() ) {
//
//            curl_setopt($curl, CURLOPT_URL, $url);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
//            $response = curl_exec($curl);
//            curl_close($curl);
//        }
//        return true;
        $data = $request->input();
        // Создание клиента
        $subdomain = Setting::where('var', 'amo_crm_domain')->first();
        $login = 'fit2u@ya.ru';
        $hash = '93bb7f29582c73c6593909c04569c297';
        $amo = new Client($subdomain->value, $login, $hash);

        // SUBDOMAIN может принимать как часть перед .amocrm.ru,
        // так и домен целиком например test.amocrm.ru или test.amocrm.com

        // Получение экземпляра модели для работы с аккаунтом
        $lead = $amo->lead;
        $lead['name'] = 'Сотрудничество';
        $contact['created_user_id'] = 0;

        $lead->addCustomField(1, $data['name'], 'name');
        $lead->addCustomField(1, $data['phone'], 'phone');
        $lead->addCustomField(1, $data['name'], 'email');
        return $lead->apiAdd();

//        try {
//        } catch (\AmoCRM\Exception $e) {
//            return [
//                'status' => 0
//            ];
//        }
    }
}
