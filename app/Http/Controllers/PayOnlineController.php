<?php

namespace App\Http\Controllers;

use App\Models\PayOnlineOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayOnlineController extends Controller
{
    /**
    * сохраняем онлайн заказ, если данные корректны
     */
    public function callback(Request $request) {
        $validator = Validator::make($request->all(), [
            'OrderId' => 'required',
            'Amount' => 'required',
            'Provider' => 'required',
            'DateTime' => 'required',
        ]);
        if(!$validator->fails()) {
            $success = 1 - $request->input('sl_error', 0);
            $data = [
                'order_id' => $request->input('OrderId'),
                'amount' => $request->input('Amount'),
                'provider' => $request->input('Provider'),
                'date' => $request->input('DateTime'),
                'success' => $success,
            ];
            $order = PayOnlineOrder::create($data);
        }
        if(!isset($order)) {
            return response('Неверные параметры запроса', 400);
        } else {
            return response('Принято');
        }
    }
}
