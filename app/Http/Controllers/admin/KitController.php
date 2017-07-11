<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Kit;
use App\Http\Requests\admin\KitRequest;
use App\Models\Product;

class KitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Kit);
        $q = Kit::with('products');
        $filters = $this->getFormFilter($request->input());
        if (!empty($filters) && !empty($filters['name']))
          $q->where('name', 'LIKE', '%'.$filters['name'].'%');
        $kits = $q->paginate($filters['perpage']);
        return view('admin.kits.index', compact('kits', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('index', new Kit);
        $kit = new Kit;
        $products = Product::published()->get();
        return view('admin.kits.create', compact('kit','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KitRequest $request)
    {
        $this->authorize('index', new Kit);
        $kit = Kit::create($request->all());
        $kit->products()->sync($request->products);
        return redirect()->route('admin.kits.index')->withMessage('Комплект добавлен');
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kit  $kit
     * @return \Illuminate\Http\Response
     */
    public function edit(Kit $kit)
    {
        $this->authorize('index', new Kit);
        $products = Product::published()->get();
        return view('admin.kits.edit', compact('kit', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kit  $kit
     * @return \Illuminate\Http\Response
     */
    public function update(KitRequest $request, Kit $kit)
    {
        $this->authorize('index', new Kit);
        $kit->update($request->all());
        $kit->products()->sync($request->products);
        return redirect()->route('admin.kits.index')->withMessage('Комплект обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kit  $kit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kit $kit)
    {
        $this->authorize('index', new Kit);
        $kit->delete();
        return redirect()->route('admin.kits.index')->withMessage('Комплект удален');
    }
}
