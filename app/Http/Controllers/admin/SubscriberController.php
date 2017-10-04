<?php

namespace App\Http\Controllers\admin;

use App\Models\Subscriber;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SubscriberController extends Controller
{
    //
    public function xls() {
        Excel::create('subscribers', function($excel) {
            $excel->sheet('Электронные адреса подписчиков', function($sheet) {
                $subscribers = Subscriber::select('email')->published()->get()->toArray();
                $sheet->fromArray($subscribers, null, 'A1', false, false);

            });
        })->download('xls');
    }
}
