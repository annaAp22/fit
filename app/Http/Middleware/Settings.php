<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use View;

class Settings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $settings = Setting::all();
        $result = collect();
        foreach($settings as $setting) {
            $setting->valueUnmarshall();
            $result->put($setting->var, $setting);
        }
        //in development mode can be used in substitution of the address.
        $site_url = env('DEV_SITE_URL', $request->root());
        $result->put('site_url', $site_url);
        //достаем только цифры с номеров телефона
        if(isset($result['phone_number'])) {
            $arr = [];
            foreach ($result['phone_number']->value as $key => $value) {
                $p = strpos($value, '+');
                if($p === false) {
                    $p = strpos($value, ':');
                }
                if($p === false) {
                    $arr[$key] = '';
                    continue;
                }
                $s = substr($value,  $p + 1, 11);
                $s = str_replace('+', '', $s);
                $arr[$key] = $s;
            }
            $result['phone_number']->number = $arr;
        }
        $month_arr = array(
            'january',
            'february',
            'march',
            'april',
            'may',
            'june',
            'july',
            'august',
            'september',
            'october',
            'november',
            'december'
        );
        $result->put('month_arr', $month_arr);
        View::share(['global_settings' => $result]);

        return $next($request);
    }
}
