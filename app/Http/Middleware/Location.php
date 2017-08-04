<?php

namespace App\Http\Middleware;

use Closure;
use Location as GeoLocation;
use Request;
use View;

class Location
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
//        $ip = env('APP_ENV') == 'production' ? Request::ip() : '217.194.255.193';
//        $location = GeoLocation::get($ip);
//
//
//        if( !isset($_COOKIE['city']) )
//        {
//            $cityName = $location->cityName ?: 'Москва';
//            setcookie( "city", $cityName, time()+(3600 * 24 * 30) );
//            $user_city = $cityName;
//        }
//        else
//        {
//            $user_city = $_COOKIE['city'];
//        }
//        View::share([
//            'geo_location' => $location,
//            'user_city' => $user_city,
//        ]);
        return $next($request);
    }
}
