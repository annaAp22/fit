<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('sysname', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-Z0-9_-]+$/u', $value);
        });

        Validator::extend('youtube_code', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-Z0-9_-]{11}$/u', $value);
        });

        Validator::extend('not_empty', function($attribute, $value, $parameters, $validator) {
            if(gettype($value) == 'array') {
                if(!empty($value) && !empty($value[0])) {
                    return true;
                }
            } elseif(!empty($value)) {
                return true;
            }
            return false;
        });

        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
