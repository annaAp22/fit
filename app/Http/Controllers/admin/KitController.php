<?php

namespace App\Http\Controllers\admin;

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
    public function index()
    {
        $this->authorize('index', new Kit);
        $kits = Kit::with('products')->paginate();

        return view('admin.kits.index', compact('kits'));
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
