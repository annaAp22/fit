<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        $group = $request->user()->group()->first();
        //disabled denied in admin panel
        if ( !isset($group->name) || $group->name == 'customer' ) {
            // Redirect...
            abort(404);
        }
        return $next($request);
    }
}
