<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\admin\LookRequest;
use Illuminate\Http\Request;
use App\Models\Look;
use App\Models\Product;
use App\Models\LookCategory;

class LookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Look());
        $filters = $this->getFormFilter($request->input());

        $looks = Look::with('products', 'category')->orderBy('id', 'desc');

        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $looks->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['category_id']) && $filters['category_id']!='') {
            $looks->where('category_id', $filters['category_id']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $looks->withTrashed();
        }
        $looks = $looks->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        $categories = LookCategory::sort()->get();

        return view('admin.looks.index', compact('looks', 'filters', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = collect([]);
        if(old()) {
            if(old('products') && !empty(old('products'))) {
                $products = Product::whereIn('id', old('products'))->get();
            }
        }
        $categories = LookCategory::sort()->get();
        return view('admin.looks.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LookRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LookRequest $request)
    {
        $data = $request->all();
        $look = new Look($data);
        $look->uploads->upload();
        $look->save();

        if($request->has('products'))
        {
            foreach($request->input('products') as $key => $product_id)
            {
                $position = json_encode($data['dots'][$key]);
                $look->products()->attach($product_id, compact('position'));
            }
        }

        return redirect()->route('admin.looks.index')->withMessage('Look добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $look = Look::with('products', 'category')->findOrFail($id);

        $products = compact([]);
        if(old()) {
            if(old('products') && !empty(old('products'))) {
                $products = Product::whereIn('id', old('products'))->get();
            }
        } else {
            $products = $look->products;
        }

        $categories = LookCategory::sort()->get();

        return view('admin.looks.edit', compact('look', 'products', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LookRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(LookRequest $request, $id)
    {
        $look = Look::findOrFail($id);
        $old_image = $look->image;
        $data = $request->all();
        $look->update($data);

        if( $products = $request->input('products') )
        {
            $sync = [];
            foreach($products as $key => $product_id)
            {
                $position = json_encode($data['dots'][$key]);
                $sync[$product_id] = compact('position');
            }
            $look->products()->sync($sync);
        }


        if($request->file('image'))
        {
            $look->uploads->upload();
            $look->save();
        }

        return redirect()->route('admin.looks.index')->withMessage('Look изменён');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Look::destroy($id);
        return redirect()->route('admin.looks.index')->withMessage('Look удалён');
    }

    public function remove($id)
    {
        $product = Look::onlyTrashed()
            ->where('id', $id)
            ->first();
        // Force deleting a single model instance...
        $product->forceDelete();

        return redirect()->route('admin.looks.index')->withMessage('Look удален');
    }

    public function restore($id) {
        Look::withTrashed()->find($id)->restore();
        return redirect()->route('admin.looks.index')->withMessage('Look востановлена');
    }
}
