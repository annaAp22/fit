<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\ProductComment;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Setting;
use Validator;
use DB;
use Lang;

class CatalogController extends Controller
{
    /**
     * Кол-во товаров на первом скрине, на страницах с фильтрацией
     * @var int
     */
    private $perpage = 20;

    public function catalogRoot() {
        $categories = Category::with([
            'products' => function($query) {
                $query->where('status', 1);
            },
            'children'
        ])
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('sort', 'desc')
            ->get();

        $count = 0;
        foreach($categories as $cat) $count += $cat->productsCount;

        return view('catalog.categories', [
            'category'       => 'root',
            'categories'     => $categories,
            'products_count' => $count,
        ]);
    }

    /**
     * Страница каталога - товары из каталога | категории из каталога
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function catalog($sysname) {

        $category = Category::with([
                'parent',
                'children_rec',
                'products.attributes' => function($query) {
                        $query->where('attributes.is_filter', 1);
                }
            ])
            ->sysname($sysname)
            ->published()
            ->firstOrFail();

        $this->setMetaTags(null, $category->title, $category->description, $category->keywords);

        // Get zero level parent id
        if($category->parent)
        {
                // if level 2 or level 1
                $parent_zero_id = $category->parent->parent_id ?: $category->parent->id;
        }
        else
        {
            // if level 0
            $parent_zero_id = $category->id;
        }

        // Количество товаров на странице
        $perPage = intval(Setting::getVar('perpage')) ?: $this->perpage;

        // Если категория родительская, то выводим листинг подкатегорий
        if($category->hasChildren) {

            // Скрыто пока нет вида для главных категорий
//            $categories = Category::with(['products'])
//                ->childrenOf($category)
//                ->published()
//                ->get();
//            return view('catalog.categories', compact('category', 'categories'));

            // Получение товаров дочерних категорий
            $category_ids = $category->children_ids($category, collect([]));
            $products = Product::with('attributes')
                                    ->whereHas('categories',
                                        function($query) use($category_ids) {
                                            $query->whereIn('categories.id', $category_ids);
                                        })
                                    ->published()
                                    ->paginate($perPage);

            $products->min_price = Product::whereHas('categories',
                function($query) use($category_ids) {
                    $query->whereIn('categories.id', $category_ids);
                })
                ->published()
                ->min('price');

            $products->max_price = Product::whereHas('categories',
                function($query) use($category_ids) {
                    $query->whereIn('categories.id', $category_ids);
                })
                ->published()
                ->max('price');

            // All category products with attributes
            $category->products = Product::with(['attributes' => function($query) {
                    $query->where('attributes.is_filter', 1)->withPivot('value')->select('attributes.*', 'attribute_product.value');
                }])
                ->whereHas('categories',
                    function($query) use($category_ids) {
                        $query->whereIn('categories.id', $category_ids);
                    })
                ->select('id')
                ->published()
                ->get();

            return view('catalog.catalog', compact('category', 'products', 'banners', 'parent_zero_id'));


        } else {
            $banners = Banner::where('type', 'content')->where('status', 1)->get();
            $products = $category
                ->products()
                ->with('attributes')
                ->published()
                ->paginate($perPage);

            $products->min_price = $category->products->where('status', 1)->min('price');
            $products->max_price = $category->products->where('status', 1)->max('price');

            return view('catalog.catalog', compact('category', 'products', 'banners', 'parent_zero_id'));
        }
    }

    /**
     * Страница тэгов - товары с этим тегом
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tags($sysname) {
        $tag = Tag::where('sysname', $sysname)->where('status', 1)->firstOrFail();
        $products = $tag->products()->where('status', 1)->paginate(3);//Setting::getVar('perpage') ?: $this->perpage);
        $products->min_price = $tag->products->where('status', 1)->min('price');
        $products->max_price = $tag->products->where('status', 1)->max('price');

        $this->setMetaTags(null, $tag->title, $tag->description, $tag->keywords);
        return view('catalog.catalog', ['tag' => $tag, 'products' => $products]);
    }

    /**
     * Страница бренда - товары этого бренда
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function brands($sysname) {
        $brand = Brand::where('sysname', $sysname)->where('status', 1)->firstOrFail();
        $products = Product::where('brand_id', $brand->id)->where('status', 1)->orderBy('name')->paginate(3);//$this->perpage);
        $products->min_price = Product::where('brand_id', $brand->id)->where('status', 1)->min('price');
        $products->max_price = Product::where('brand_id', $brand->id)->where('status', 1)->max('price');

        $this->setMetaTags(null, $brand->title, $brand->description, $brand->keywords);
        return view('catalog.catalog', ['brand' => $brand, 'products' => $products]);
    }

    /**
     * Страница акции - товары с ярлыком "Акция"
     * @param $sysname - чпу родительской категории
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actions($sysname = null) {
        if($sysname)
        {
            $category = Category::with('children_rec')->sysname($sysname)->firstOrFail();
            $category_ids = $category->children_ids($category, collect([]));
            $products = Product::with('attributes')
                ->whereHas('categories',
                    function($query) use($category_ids) {
                        $query->whereIn('categories.id', $category_ids);
                    })
                ->where('act', 1)
                ->published();
        }
        else
        {
            $products = Product::where('act', 1)->where('status', 1)->orderBy('name');
        }

        $products = $products->paginate(Setting::getVar('perpage') ?: $this->perpage);
        $products->min_price = Product::where('act', 1)->where('status', 1)->min('price');
        $products->max_price = Product::where('act', 1)->where('status', 1)->max('price');

        $page = $this->setMetaTags();
        if($page && isset($category))
        {
            $page->category = $category;
            $page->sysname = $category->sysname;
        }
        return view('catalog.catalog', compact('products', 'page'));
    }

    /**
     * Страница новинки - товары с ярлыком "Новинка"
     * @param $sysname - ЧПУ родительской категории
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newProducts($sysname = null) {
        if($sysname)
        {
            $category = Category::with('children_rec')->sysname($sysname)->firstOrFail();
            $category_ids = $category->children_ids($category, collect([]));
            $products = Product::with('attributes')
                ->whereHas('categories',
                    function($query) use($category_ids) {
                        $query->whereIn('categories.id', $category_ids);
                    })
                ->where('new', 1)
                ->published();
        }
        else
        {
            $products = Product::where('new', 1)->where('status', 1)->orderBy('name');
        }

        $products = $products->paginate(Setting::getVar('perpage') ?: $this->perpage);


        $products->min_price = Product::where('new', 1)->where('status', 1)->min('price');
        $products->max_price = Product::where('new', 1)->where('status', 1)->max('price');

        $page = $this->setMetaTags();
        if($page && isset($category))
        {
            $page->category = $category;
            $page->sysname = $category->sysname;
        }
        return view('catalog.catalog', compact('products', 'page'));
    }

    /**
     * Страница выбор покупателей - товары с ярлыком "Хит"
     * @param $sysname - ЧПУ родительской категории
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hits($sysname = null) {
        if($sysname)
        {
            $category = Category::with('children_rec')->sysname($sysname)->firstOrFail();
            $category_ids = $category->children_ids($category, collect([]));
            $products = Product::with('attributes')
                ->whereHas('categories',
                    function($query) use($category_ids) {
                        $query->whereIn('categories.id', $category_ids);
                    })
                ->where('hit', 1)
                ->published();
        }
        else
        {
            $products = Product::where('hit', 1)->where('status', 1)->orderBy('name');
        }

        $products = $products->paginate(Setting::getVar('perpage') ?: $this->perpage);
        $products->min_price = Product::where('hit', 1)->where('status', 1)->min('price');
        $products->max_price = Product::where('hit', 1)->where('status', 1)->max('price');

        $page = $this->setMetaTags();
        if($page && isset($category))
        {
            $page->category = $category;
            $page->sysname = $category->sysname;
        }
        return view('catalog.catalog', compact('products', 'page'));
    }

    /**
     * Результаты поиска
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request) {
        //TODO: переделать на полнотекстовой через эластиксеарч
        $per_page = $request->input('per_page', 20) == 'all'?400:20;
        if($request->has('text') && $request->input('text') !='') {
            $products = Product::where('name','LIKE' , '%'.$request->input('text').'%')->where('status', 1)->orderBy('name')->paginate($per_page);
        }

        if($request->isXmlHttpRequest()) {
            $nextPage = ($products->currentPage() == $products->lastPage()) ? false : $products->currentPage() + 1;
            return response()->json([
                'html' => view('catalog.products.list', [
                    'products' => $products,
                ])->render(),
                'nextPage' => $nextPage,
                'total' => $products->total(),
                'count' => $products->total() - $products->currentPage() * $products->perPage(),
                'action' => 'appendGoods',
                'clear' => $request->input('per_page', 1) == 'all',
                'text' => $request->input('text'),
            ]);
        } else {
            $this->setMetaTags();
            return view('catalog.search', [
                'products' => !empty($products) ? $products : null,
                'text' => $request->input('text'),
            ]);
        }
    }

    /**
     * Страница отложенных товаров
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookmarks() {
        $products = collect();
        if(session()->has('products.defer') && $defers = session()->get('products.defer')) {
            //выводим последние 48 товара
            arsort($defers);
            $defers = array_slice(array_keys($defers), 0, 48);
            $products = Product::whereIn('id', $defers)->where('status', 1)->orderByRaw('FIELD(id, '.implode(',', $defers).')')->take(48)->get();
        }
        $this->setMetaTags();
        return view('catalog.bookmarks', compact('products'));
    }

    /**
     * Страница просмотренных товаров
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seen() {
        $products = collect();
        if(session()->has('products.view')) {
            //выводим последние 48 товара
            $views = session()->get('products.view');
            arsort($views);
            $views = array_slice(array_keys($views), 0, 48);
            $products = Product::whereIn('id', $views)->where('status', 1)->orderByRaw('FIELD(id, '.implode(',', $views).')')->take(48)->get();
        }

        $this->setMetaTags();
        return view('catalog.seen', compact('products'));
    }


    /**
     * Карточка товара
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function product($sysname) {
        $product = Product::with([
            'brand',
            'categories',
            'photos',
            'attributes',
            'kits.products.attributes',
            'related.attributes',
        ])->where('sysname', $sysname)->where('status', 1)->firstOrFail();
        $comments = $product->comments()->published()->paginate(5);
        $this->setMetaTags(null, $product->title, $product->description, $product->keywords);

        //добавляем товар в просмотренные
        if(!session()->has('products.view.'.$product->id)) {
            session()->put('products.view.' . $product->id, 1);
        }
        //аналоги
        $analogues = Product::where('id', '!=', $product->id)->whereHas('categories', function ($query) use ($product) {
            $query->whereIn('category_id', $product->categories->pluck('id')->toArray());
        })->inRandomOrder()->take(10)->get();

        return view('catalog.products.details', [
            'product' => $product,
            'analogues' => $analogues,
            'comments' => $comments,
        ]);
    }



}
