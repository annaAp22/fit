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
        $ip = env('APP_ENV') == 'production' ? Request::ip() : '217.194.255.193';
        View::share([
            'geo_location' => GeoLocation::get($ip),
        ]);
        return $next($request);
    }
}
