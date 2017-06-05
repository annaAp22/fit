<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\admin\ShopRequest;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Shop;

class ShopController extends Controller
{
  /*
   * @return cities items view
   * */
  public function index()
  {
    $shop = new Shop();
    $this->authorize('index', $shop);
    $items = Shop::all();
    return view('admin.shops.index', compact('items'));
  }

  public function create()
  {
    $cities = City::published()->get();
    return view('admin.shops.create', compact('cities'));
  }
  public function store(ShopRequest $request)
  {
    Shop::create($request->all());
    return redirect()->route('admin.shops.index')->withMessage('Магазин добавлен');
  }
  public function edit($id)
  {
    $shop = Shop::findOrFail($id);
    $cities = City::published()->get();
    return view('admin.shops.edit', compact('shop', 'cities'));
  }
  public function update(ShopRequest $request, $id)
  {
    Shop::findOrFail($id)->update($request->all());
    return redirect()->route('admin.shops.index')->withMessage('Магазин изменен');
  }
  public function destroy($id)
  {
    Shop::destroy($id);
    return redirect()->route('admin.shops.index')->withMessage('Магазин удален');
  }

}
