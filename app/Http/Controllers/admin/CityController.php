<?php

namespace App\Http\Controllers\admin;

use App\Models\City;
use App\Http\Requests\admin\CityRequest;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
  /*
   * @return cities items view
   * */
  public function index()
  {
    $city = new City();
    $this->authorize('index', $city);
    $items = City::all();
    return view('admin.cities.index', compact('items'));
  }

  public function create()
  {
    return view('admin.cities.create');
  }
  public function store(CityRequest $request)
  {
    City::create($request->all());
    return redirect()->route('admin.cities.index')->withMessage('Город добавлен');
  }
  public function edit($id)
  {
    $city = City::findOrFail($id);
    return view('admin.cities.edit', compact('city'));
  }
  public function update(CityRequest $request, $id)
  {
    City::findOrFail($id)->update($request->all());
    return redirect()->route('admin.cities.index')->withMessage('Город изменен');
  }
  public function destroy($id)
  {
    City::destroy($id);
    return redirect()->route('admin.cities.index')->withMessage('Город удален');
  }

}
