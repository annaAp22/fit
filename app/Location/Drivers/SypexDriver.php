<?php
namespace App\Location\Drivers;

use Illuminate\Support\Fluent;
use Stevebauman\Location\Position;
use Stevebauman\Location\Drivers\Driver;

class SypexDriver extends Driver
{
    public function url()
    {
        return 'https://api.sypexgeo.net/json/';
    }

    protected function hydrate(Position $position, Fluent $location)
    {
        $position->isoCode = $location->country['iso'];
        $position->countryCode = $location->country['iso'];
        $position->countryName = $location->country['name_ru'];

        $position->regionCode = $location->region['iso'];
        $position->regionName = $location->region['name_ru'];

        $position->cityName = $location->city['name_ru'];
        $position->latitude = $location->city['lat'];
        $position->longitude = $location->city['lon'];
        $position->areaCode = $location->country['phone']; // Not sure if this is right

        $position->metroCode = 'Unavailable with SypexGeo driver.';
        $position->postalCode = 'Unavailable with SypexGeo driver.';
        $position->zipCode = 'Unavailable with SypexGeo driver.';

        return $position;
    }

    protected function process($ip)
    {
        try {
            $response = json_decode(file_get_contents($this->url().$ip), true);

            return new Fluent($response);
        } catch (\Exception $e) {
            return false;
        }
    }
}