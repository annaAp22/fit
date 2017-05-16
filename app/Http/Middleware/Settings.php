<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
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
        View::share(['global_settings' => $result]);

        return $next($request);
    }
}
