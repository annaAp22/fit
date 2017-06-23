<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
      // Using Closure based composers...
      View::composer('*', function ($view) {
        $defer = \Session::get('products.defer') ?: [];
        $seen = \Session::get('products.view') ?: [];
        $view->with('defer', array_keys($defer) )
            ->with('seen', array_keys($seen) );
      });
      View::composer(['errors::403','errors.403','errors::404','errors.404', 'errors::500', 'errors.500', 'errors::503', 'errors.503'], function ($view) {
        $settings = Setting::all();
        $result = collect();
        foreach($settings as $setting) {
          $setting->valueUnmarshall();
          $result->put($setting->var, $setting);
        }
        $view->with('global_settings', $result);
      });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}