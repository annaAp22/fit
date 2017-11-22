<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use AmoCRM\Client;
use Illuminate\Support\Facades\Log;

class AmoCRMController extends Controller
{
    function cooperationOrder(Request $request) {
        $url = 'http://fresh24.bz/siteform/intercept';
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'hash' => '93bb7f29582c73c6593909c04569c297',
            //"hash" : "3942499be057166c9d2dbefd2eeaf607",
        ];
        if( $curl = curl_init() ) {

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $data));
            $response = curl_exec($curl);
            curl_close($curl);
        }
        Log::info($response);
        return $response;
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
