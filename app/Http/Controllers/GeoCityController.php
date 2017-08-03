<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoCity;

class GeoCityController extends Controller
{
    public function citiesByRegion(Request $request)
    {
        $region = $request->input('region');
        if($region)
        {
            $cities = GeoCity::regionCities($region)->get();
            $html = View('widgets.select_city__cities', compact('cities'))->render();

            $res = [
                'cities' => $html,
                'action' => 'replaceGeoCities',
            ];
        }
        else
        {
            $res = [
                'error' => 1,
                'message' => 'Region not defined.',
            ];
        }

        return $res;
    }

    public function citiesAutocomplete(Request $request)
    {
        $key = $request->input('query');
        if($key)
        {
            $cities = GeoCity::citySearch($key)->get();
            $citiesAr = [];

            foreach( $cities as $city )
            {
                $citiesAr[] = $city->city;
            }

            $res = [
                'query' => $key,
                'suggestions' => $citiesAr,
            ];
        }
        else
        {
            $res = [
                'error' => 1,
                'message' => 'Search key word not defined.',
            ];
        }

        return $res;
    }
}
