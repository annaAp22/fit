<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\GeoCity;

class SelectCity extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'city' => 'Москва',
        'region' => 'Москва',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $regions = GeoCity::regions()->orderBySort()->get();
        if( isset($_COOKIE['city']) )
        {
            $this->config['city'] = htmlspecialchars($_COOKIE['city']);
            $region = GeoCity::cityRegion($this->config['city'])->first();

            if( $region )
            {
                $this->config['region'] = $region->region;
            }
            else
            {
                unset($_COOKIE['city']);
            }

        }
        $cities = GeoCity::regionCities($this->config['region'])->get();

        return view("widgets.select_city", [
            'config' => $this->config,
            'regions' => $regions,
            'cities' => $cities,
        ]);
    }
}