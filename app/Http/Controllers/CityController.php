<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Page;
use Illuminate\Routing\Route;

class CityController extends Controller
{
  //
  function index() {
    $cities = City::published()->get();
    $sysname = 'agencies';
    $page = Page::where('sysname', $sysname)->firstOrFail();
    $this->setMetaTags(null, $page->title, $page->description, $page->keywords);
    return view('agency.index', compact('cities'));
  }
  /*
   * @return city view by $sysname
   *
   * */
  public function details($sysname)
  {
    $city = City::where('sysname', $sysname)->firstOrFail();
    $this->setMetaTags(null, 'Наши представительства в городе '.$city->title);
    return view('agency.details', compact('city'));
  }
}
