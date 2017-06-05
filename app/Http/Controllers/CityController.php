<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
  //
  function index() {
    $cities = City::published()->get();
    return view('agency.index', compact('cities'));
  }
  /*
   * @return city view by $sysname
   *
   * */
  public function details($sysname)
  {
    $city = City::where('sysname', $sysname)->firstOrFail();
    return view('agency.details', compact('city'));
  }
}
