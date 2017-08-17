<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WidgetController extends \Arrilot\Widgets\Controllers\WidgetController
{
    //
    /**
     * The action to show widget output via ajax.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function showWidget(Request $request)
    {
        $this->prepareGlobals($request);
        $factory = app()->make('arrilot.widget');
        $widgetName = $request->input('name', '');
        $widgetParams = $request->input('params', '');
        return call_user_func_array([$factory, $widgetName], $widgetParams);
    }
}
