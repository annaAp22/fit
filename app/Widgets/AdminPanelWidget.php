<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Auth;
use Route;
use Request;

class AdminPanelWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if(Auth::user()) {
            $routes =  ['index' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'mainpage']],
                        'news' => ['as' => 'admin.news.index', 'attr' => 'refresh=1'],
                        'about' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'o-torgovom-centre']],
                        'services' => ['as' => 'admin.services.index', 'attr' => 'refresh=1'],
                        'services.item' => ['as' => 'admin.services.edit_sysname', 'params' => ['{sysname}' => Route::input('sysname')], 'route' => route('admin.services.edit_sysname', Route::input('sysname'))],
                        'plan' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'plan']],
                        'renters' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'arendatoram']],
                        'advs' => ['as' => 'admin.advs.index', 'attr' => 'refresh=1'],
                        'contacts' => ['as' => 'admin.contacts.index', 'attr' => 'refresh=1'],
                        'price' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'price']],
                        'dogovor' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'dogovor-arendy-tp']],
                        'dogovor_of' => ['as' => 'admin.pages.edit_sysname', 'params' => ['{sysname}' => 'dogovor-arendy-of']],
            ];

            $url = '';
            if(empty($routes[Route::currentRouteName()])) {
                return ' ';
            }

            $route = $routes[Route::currentRouteName()];
            if($route && $route['as']) {
                if(!empty($route['params'])) {
                    $url = route($route['as'], $route['params']);
                } else {
                    $url = route($route['as'], []);
                }
                if(!empty($route['attr'])) {
                    $url .= '?'.$route['attr'];
                }
            }

            $url_route = (isset($route['route']) ? $route['route'] : route('admin.metatags.edit_route', Route::currentRouteName()));

            return view("widgets.admin_panel_widget", [
                'config' => $this->config, 'url' => $url, 'url_route' => $url_route
            ]);
        } else {
            return ' ';
        }
    }
}