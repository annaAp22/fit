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
