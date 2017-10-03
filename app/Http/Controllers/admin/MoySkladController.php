<?php

namespace App\Http\Controllers\admin;

use App\Library\MoySklad\Ms;
use App\Models\MsCronCounter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
class MoySkladController extends \App\Http\Controllers\MoySkladController
{
    //
    public function updateProducts() {
        $message = $this->importProducts(new Ms());
        if(!$message) {
            $message = 'Сервер не отвечает, попробуйте повторить через несколько минут';
        }else {
            $message = str_replace('Products synced count', 'Синхронизировано товаров', $message);
        }
        return redirect()->route('admin.moysklad.sync')->with('message', $message);
    }
    public function sync() {
        $cron_counter = MsCronCounter::all()->keyBy('action');
        return view('admin.moysklad.sync', compact('cron_counter'));
    }
    public function updateAttributes() {
        $message = $this->updatePriceAndStock(new Ms());
        if(!$message) {
            $message = 'Сервер не отвечает, попробуйте повторить через несколько минут';
        }else {

        }
        return redirect()->route('admin.moysklad.sync')->with('message', $message);
    }
}
