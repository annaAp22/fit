<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Kit;
use App\Models\Product;
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

        $products = Product::with('categories.children', 'brand', 'tags');

        $filters = $this->getFormFilter($request->input());
        if(!empty($filters)) {
            if (!empty($filters['name'])) {
                $products->where('name', 'LIKE', '%'.$filters['name'].'%');
            }
            if (!empty($filters['sysname']))
                $products->where('sysname', 'LIKE', '%'.$filters['sysname'].'%');
            if (!empty($filters['id_category']))
                $products->whereHas('categories', function ($query) use ($filters) {
                    $query->where('category_id', $filters['id_category']);
                });
            if (!empty($filters['brand_id']))
                $products->where('brand_id', $filters['brand_id']);
            if (!empty($filters['tag']))
                $products->whereHas('tags', function ($query) use ($filters) {
                    $query->where('tag_id', $filters['tag']);
                });
            if (!empty($filters['attributes']))
                foreach($filters['attributes'] as $attribute_id => $value) {
                    if($value) {
                        $products->whereHas('attributes', function ($query) use ($value, $attribute_id) {
                            $query->where('attribute_id', $attribute_id)->where('value', $value);
                        });
                    }
                }
            if (isset($filters['status']) && $filters['status']!='')
                $products->where('status', $filters['status']);
            if (isset($filters['deleted']) && $filters['deleted'])
                $products->withTrashed();
            if (!empty($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'hit':$products->orderBy('hit', 'desc');break;
                    case 'act':$products->orderBy('act', 'desc');break;
                    case 'new':$products->orderBy('new', 'desc');break;
                    case 'cheaper':$products->orderBy('price');break;
                    case 'expensive':$products->orderBy('price', 'desc');break;
                    default:$products->orderBy('id', 'desc');
                }
            }
        }
        $products   = $products->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);
        $categories = Category::with('children.children')->roots()->orderBy('sort')->get();
        $tags       = Tag::orderBy('views', 'desc')->orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();
        $attributes = Attribute::where('is_filter', 1)->orderBy('name')->get();
        $sorts = [
            'hit' => 'сначала хиты',
            'act' => 'сначала акции',
            'new' => 'сначала новинки',
            'expensive' => 'сначала дороже',
            'cheaper' => 'сначала дешевле',
        ];
        $this->setMetaTags(null, ' Список всех товаров');
        return view('admin.products.index',compact('products', 'categories', 'tags', 'brands', 'filters', 'attributes', 'sorts'));
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
        $this->setMetaTags(null, ' Создание товара');
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
        $product->price_old = ceil( ( $product->price / (100 - $product->discount) ) * 100 );
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
        $this->setMetaTags(null, ' Редактирование товара');
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
        $checkboxes = array('hit', 'new', 'ya_market', 'merchant', 'act');
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
     * Сортировка товаров с филтром по уникальным предложеним и полу
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uniqueOffersSort(Request $request) {

        $filter = [
            'type' => $request->input('type', 'hit'),
            'sex' => $request->input('sex', 'woman'),
            'types' => [
                'hit' => 'хит',
                'new' => 'новинка',
                'act' => 'акция',
            ],
            'sex_types' => [
                'man' => 'Мужской',
                'woman' => 'Женский',
            ],
        ];
        $sort = $filter['type'].'_sort';
        $sex = $filter['sex_types'][$filter['sex']];

        $products = Product::join('attribute_product', 'products.id', 'product_id')->select('products.*')->where('value', $sex)->where($filter['type'], 1)->published()->orderBy($sort)->distinct()->get();
        //сохранение сортировки
        if($request->has('ids') && $request->has('ids.0')) {
            $data = $request->input('ids.0');
            foreach($data as $key => $product_id) {
                $product = $products->find($product_id);
                if($product) {
                    $product->update([$sort => $key]);
                }
            }
            return redirect()->back()->withMessage('Сортировка сохранена');
        }
        $this->setMetaTags(null, ' Сортировка товаров');
        return view('admin.products.sort.unique-offers', compact('filter', 'products'))->withMessage('Сортировка сохранена');
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
    /*
     * save checker
     * **/
    public function saveChecker(Request $request) {
        $data = $request->input();
        if(isset($data['checker'], $data['id'])) {
            if(!in_array($data['checker'], ['act','hit','new'])) {
                return [
                    'error' => 'Ошибка данных!',
                    'message' => 'поле отсутствует в списке разрешенных',
                ];
            }
            $product = Product::find($data['id']);
            if(isset($data['value'])) {
                //$product->$data['checker'] = 1;
                $product->update([$data['checker'] => 1]);
            }else {
                //$product->$data['checker'] = 0;
                $product->update([$data['checker'] => 0]);
            }
            //$product->save();
            return [
                'status' => 200,
                'action' => 'saveComplete',
            ];
        }else {
            return [
                'error' => 'Ошибка данных!',
                'message' => 'не удалось сохранить чекер',
            ];
        }
    }


}
