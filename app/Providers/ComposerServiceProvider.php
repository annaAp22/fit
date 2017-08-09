<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Request;
use Meta;
use Location as GeoLocation;

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
        $route = Route::current();
        $routeName = $route ? $route->getName() : '';
        $defer = \Session::get('products.defer') ?: [];
        $seen = \Session::get('products.view') ?: [];

          $ip = env('APP_ENV') == 'production' ? Request::ip() : '217.194.255.193';
          $location = GeoLocation::get($ip);


          if( !isset($_COOKIE['city']))
          {
              $cityName = isset($location->cityName) ? $location->cityName : 'Москва';
              setcookie( "city", $cityName, time()+(3600 * 24 * 30) );
              $user_city = $cityName;
          }
          else
          {
              $user_city = $_COOKIE['city'];
          }

        $view->with('defer', array_keys($defer) )
            ->with('seen', array_keys($seen) )
            ->with('user_city', $user_city)
            ->with('geo_location', $location)
            ->with('routeName', $routeName);
      });
      //передаем пользователя в представления
      View::composer(
          [
              'modals.quick_order_product', 'order.order', 'content.orders_history'
          ],
          function($view) {
            $user = Auth::check()?Auth::user():null;
            $view->with('user', $user);
      });
      //навигация по личному кабинету в боковой панели
//      View::composer(
//          [
//              'blocks.room_navigation'
//          ],
//          function($view) {
//            $routeName = Route::current()->getName();
//            $active = array(
//                'data' => ($routeName == 'room')?'active':'',
//                'orders' => ($routeName == 'orders-history')?'active':'',
//            );
//            $view->with('active', $active);
//          });


      View::composer(['errors::403','errors.403','errors::404','errors.404', 'errors::500', 'errors.500', 'errors::503', 'errors.503'], function ($view) {
        Meta::setTitle('Страница не найдена');
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