<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoCity extends Model
{
    protected $fillable = [
        'city',
        'region',
        'latitude',
        'longitude',
        'sort',
    ];

    public function scopeRegions($query)
    {
        return $query->select('region','sort')
                ->distinct();
    }

    public function scopeOrderBySort($query)
    {
        return $query->orderBy('sort', 'ASC');
    }

    public function scopeRegionCities($query, $regionName)
    {
        return $query->select('id', 'city')
                ->where('region', $regionName)
                ->orderBy('city', 'ASC');
    }

    public function scopeCityRegion($query, $cityName)
    {
        return $query->select('region')
                ->where('city', $cityName);
    }

    public function scopeCitySearch($query, $key)
    {
        return $query->select('city')
                ->where('city', 'LIKE', $key.'%')
                ->orderBy('city', 'ASC')
                ->take(10);
    }
}
