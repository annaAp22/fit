<?php

namespace App\Http\Controllers\admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Http\Controllers\Controller as BaseController;

use Route;
use Meta;

class Controller extends BaseController
{
    public function getFormFilter($input = [], $perpage = 10) {
        if(!empty($input['f'])) {
            if(empty($input['f']['perpage'])) {
                $input['f']['perpage'] = $perpage;
            }
            session()->put(Route::currentRouteName().'.filters', $input['f']);
            return $input['f'];
        } elseif(!empty($input['refresh'])) {
            session()->forget(Route::currentRouteName().'.filters');
            return ['perpage' => $perpage];
        } else {
            $sess = session()->get(Route::currentRouteName().'.filters');
            if(empty($sess) || empty($sess['perpage'])) {
                $sess['perpage'] = $perpage;
                if(!empty($input['page'])) {
                    $sess['page'] = $input['page'];
                }
            }
            return $sess;
        }
    }


}
