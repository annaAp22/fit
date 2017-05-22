<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Kit;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductPhoto;
use App\Models\Attribute;

use Validator;
use Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', Product::class);

        $products = Product::with('categories.children', 'brand', 'tags')
            ->orderBy('id', 'desc');

        $filters = $this->getFormFilter($request->input());
        if (!empty($filters) && !empty($filters['name']))
            $products->where('name', 'LIKE', '%'.$filters['name'].'%');

        if (!empty($filters) && !empty($filters['sysname']))
            $products->where('sysname', 'LIKE', '%'.$filters['sysname'].'%');

        if (!empty($filters) && !empty($filters['id_category']))
            $products->whereHas('categories', function ($query) use ($filters) {
                $query->where('category_id', $filters['id_category']);
            });

        if (!empty($filters) && !empty($filters['brand_id']))
            $products->where('brand_id', $filters['brand_id']);

        if (!empty($filters) && !empty($filters['tag']))
            $products->whereHas('tags', function ($query) use ($filters) {
                $query->where('tag_id', $filters['tag']);
            });

        if (!empty($filters) && !empty($filters['attributes']))
            foreach($filters['attributes'] as $attribute_id => $value) {
                if($value) {
                    $products->whereHas('attributes', function ($query) use ($value, $attribute_id) {
                        $query->where('attribute_id', $attribute_id)->where('value', $value);
                    });
                }
            }

        if (!empty($filters) && isset($filters['status']) && $filters['status']!='')
            $products->where('status', $filters['status']);

        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted'])
            $products->withTrashed();

        $products   = $products->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);
        $categories = Category::with('children.children')->roots()->orderBy('sort')->get();
        $tags       = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();
        $attributes = Attribute::where('is_filter', 1)->orderBy('name')->get();

        return view('admin.products.index', [
            'products'   => $products,
            'categories' => $categories,
            'tags'       => $tags,
            'brands'     => $brands,
            'filters'    => $filters,
            'attributes' => $attributes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $categories = Category::with('children.children', 'parent')->roots()->orderBy('sort')->get();
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::orderBy('name')->get();
        $kits = Kit::orderBy('name')->get();

        $related = [];
        if(old()) {
            if(old('related') && !empty(old('related'))) {
                $related = Product::whereIn('id', old('related'))->get();
            }
        }

        return view('admin.products.create', compact('categories', 'tags', 'brands', 'attributes', 'related', 'kits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\ProductRequest $request)
    {
        $data = $request->all();
        $data['brand_id'] = ($data['brand_id'] ?: null);
        $product = new Product($data);
        $product->uploads->upload();
        $product->save();

        //связь с категориями
        if($request->has('categories')) {
            foreach($request->input('categories') as $key => $id_category) {
                $product->categories()->attach($id_category, ['sort' => 0]);
            }
        }

        if($request->file('photos')) {
            foreach($request->file('photos') as $key => $photo) {
                $validator = Validator::make(['img' => $photo], ProductPhoto::$rules);
                if ($validator->passes()) {
                    $photo = new ProductPhoto(['product_id' => $product->id]);
                    $photo->uploads->upload(['img' => 'photos.'.$key]);
                    $photo->save();
                }
            }
        }

        if($request->has('tags')) {
            foreach($request->input('tags') as $key => $id_tag) {
                $product->tags()->attach($id_tag, ['sort' => 0]);
            }
        }
        //связь с "покупают также"
        if($request->has('related')) {
            $product->related()->sync(array_diff($request->input('related'), ['']));
        }

        if($request->has('kits'))
            $product->kits()->sync($request->kits);

        //связь с атрибутами
        if(!$product->setAttributesFromRequest())
            Session::flash('error', 'Не все атрибуты товара были сохранены.');

        return redirect()->route('admin.products.index')->withMessage('Товар добавлен');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('brand', 'categories.children', 'tags')->findOrFail($id);
        $product->price_old = $product->priceOriginal;
        $categories = Category::with('children.children')->where('parent_id', 0)->orderBy('sort')->get();
        $tags = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::orderBy('name')->get();
        $kits = Kit::orderBy('name')->get();

        $related = [];
        if(old()) {
            if(old('related') && !empty(old('related'))) {
                $related = Product::whereIn('id', old('related'))->get();
            }
        } else {
            $related = $product->related;
        }

        return view('admin.products.edit', compact('product', 'categories', 'tags','brands', 'related', 'attributes', 'kits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->all();
        $checkboxes = array('hit', 'new');
        foreach ($checkboxes as $ch) {
            if(!isset($data[$ch])) {
                $data[$ch] = 0;
            }
        }
        $data['brand_id'] = ($data['brand_id'] ?: null);

        $product->update($data);

        //связь с категориями
        $product->categories()->sync($request->input('categories'));

        //delete photos
        if($request->has('p_ids')) {
            foreach($data['p_ids'] as $key => $id_photo) {
                $photo = $product->photos()->find($id_photo);
                if(!empty($photo)) {
                    //delete
                    if (!empty($data['p_delete'][$key])) {
                        $photo->uploads->delete();
                        $photo->delete();
                    }
                }
            }
        }

        //добавление основного изображения
        if($img = $request->file('img')) {
            $validator = Validator::make(['img' => $img], ProductPhoto::$rules);
            if ($validator->passes()) {
                $product->uploads->upload();
                $product->save();
            }
        }

        //добавление дополнительных изображений
        if($request->file('photos')) {
            foreach($request->file('photos') as $key => $photo) {
                $validator = Validator::make(['img' => $photo], ProductPhoto::$rules);
                if ($validator->passes()) {
                    $photo = new ProductPhoto(['product_id' => $product->id]);
                    $photo->uploads->upload(['img' => 'photos.'.$key]);
                    $product->photos()->save($photo);
                }
            }
        }

        //связь с тегами
        if($request->has('tags')) {
            $product->tags()->sync($request->input('tags'));
        }elseif($product->tags()->count()) {
            foreach($product->tags as $tag) {
                $product->tags()->detach($tag->id);
            }
        }

        if($request->has('kits'))
            $product->kits()->sync($request->kits);
        elseif($product->kits()->count())
            foreach($product->kits as $kit)
                $product->kits()->detach($kit->id);

        //связь с "покупают также"
        if($request->has('related'))
            $product->related()->sync(array_diff($request->input('related'), ['']));

        elseif($product->related()->count())
            foreach($product->related as $product)
                $product->related()->detach($product->id);

        //связь с атрибутами
        if(!$product->setAttributesFromRequest(true))
            Session::flash('error', 'Не все атрибуты товара были сохранены.');

        return redirect()->route('admin.products.index')->withMessage('Товар изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('admin.products.index')->withMessage('Товар удален');
    }

    /**
     * @param $id - product id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
       $product = Product::onlyTrashed()
                       ->where('id', $id)
                       ->first();
        // Force deleting a single model instance...
        $product->forceDelete();

        return redirect()->route('admin.products.index')->withMessage('Товар удален');
    }

    /**
     * Востановление мягко удаленного товара
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        Product::withTrashed()->find($id)->restore();
        return redirect()->route('admin.products.index')->withMessage('Товар востановлена');
    }

    /**
     * Поиск по товарам в автоподстановке
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request) {
        $data =[];
        if($request->has('search_param') && $request->input('search_param')) {
            $products = Product::where('name', 'LIKE', '%'.$request->input('search_param').'%')->orderBy('id', 'desc')->take(10)->get();
            foreach ($products as $product) {
                $data[] = ['id' => $product->id, 'name' => $product->name];
            }
        }
        return response()->json($data);
    }

    /**
     * Сортировка товаров внутри категории
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortCategory($id) {
        $category = Category::find($id);
        $products = $category->products()->get();
        return view('admin.products.sort.category', ['category' => $category, 'products' => $products]);
    }

    /**
     * Сохранение сортировки внутри категории
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function sortCategorySave($id, Request $request) {
        $category = Category::find($id);

        //сохранение сортировки
        if($request->has('ids') && $request->has('ids.0')) {
            $data = [];
            foreach($request->input('ids.0') as $sort => $product_id) {
                $data[$product_id] = ['sort' => $sort];
            }
            $category->products()->sync($data);
        }
        //сохранение наличия и цен
        if($request->has('prices')) {
            foreach($request->input('prices') as $id => $price) {
                Product::findOrFail($id)->update(['price' => $price, 'stock' => ($request->has('stocks.'.$id) ? 1 : 0)]);
            }
        }
        return redirect()->back()->withMessage('Сортировка сохранена');
    }

    /**
     * Сортировка товаров внутри тэга
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortTag($id) {
        $tag = Tag::find($id);
        $products = $tag->products()->get();
        return view('admin.products.sort.tag', ['tag' => $tag, 'products' => $products]);
    }

    /**
     * Сохранение сортировки внутри тэга
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function sortTagSave($id, Request $request) {
        $tag = Tag::find($id);
        if($request->has('ids') && $request->has('ids.0')) {
            $data = [];
            foreach($request->input('ids.0') as $sort => $product_id) {
                $data[$product_id] = ['sort' => $sort];
            }
            $tag->products()->sync($data);
        }
        //сохранение наличия и цен
        if($request->has('prices')) {
            foreach($request->input('prices') as $id => $price) {
                Product::findOrFail($id)->update(['price' => $price, 'stock' => ($request->has('stocks.'.$id) ? 1 : 0)]);
            }
        }
        return redirect()->back()->withMessage('Сортировка сохранена');
    }



}
