<?php

namespace App\Providers;

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