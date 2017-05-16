<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Brand());
        $filters = $this->getFormFilter($request->input());

        $brands = Brand::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['name'])) {
            $brands->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        if (!empty($filters) && !empty($filters['sysname'])) {
            $brands->where('sysname', 'LIKE', '%'.$filters['sysname'].'%');
        }
        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $brands->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $brands->withTrashed();
        }
        $brands = $brands->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.brands.index', ['brands' => $brands, 'filters' => $filters]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\BrandRequest $request)
    {
        $data = $request->all();
        $brand = new Brand($data);
        $brand->uploads->upload();
        $brand->save();
        return redirect()->route('admin.brands.index')->withMessage('Бренд добавлен');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', ['brand' => $brand]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $data = $request->all();
        $brand->uploads->upload();
        $brand->update($data);
        return redirect()->route('admin.brands.index')->withMessage('Бренд изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Brand::destroy($id);
        return redirect()->route('admin.brands.index')->withMessage('Бренд удален');
    }


    /**
     * Востановление мягко удаленной категории
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        Brand::withTrashed()->find($id)->restore();
        return redirect()->route('admin.brands.index')->withMessage('Бренд востановлен');
    }

}
