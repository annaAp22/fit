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
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
  /**
   * Кол-во товаров на первом скрине, на страницах с фильтрацией
   * @var int
   */
  private $perpage = 20;
  //products count for current cutegory without filters
  private $totalProductsCount;
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
  public function saveFilters(Request $request) {
    if(!$request->has('filter')) {
      return;
    }
    $filters = array(
        'page' => $request->input('page'),
        'pageCount' => $request->input('pageCount')
    );
    //фильтр по бренду
    if($request->has('brand_id') && $request->input('brand_id')) {
      $brand_id = intval($request->input('brand_id'));
      $filters['brand_id'] = $brand_id;
    }
    //фильтр по акции
    if($request->has('act') && $request->input('act')) {
      $filters['act'] = 1;
    }
    //фильтр по Новинкам
    if($request->has('new') && $request->input('new')) {
      $filters['new'] = 1;
    }
    //фильтр по Хитам
    if($request->has('hit') && $request->input('hit')) {
      $filters['hit'] = 1;
    }
    //фильтр по ценам от - до
    if($request->has('price_from') && $request->input('price_from')) {
      $price_from = intval($request->input('price_from'));
      $filters['startPrice'] = $price_from;
    }
    if($request->has('price_to') && $request->input('price_to')) {
      $price_to = intval($request->input('price_to'));
      $filters['endPrice'] = $price_to;
    }
    //фильтры по атрибутам
    if ($request->has('attribute')) {
      $filters['attributes'] = $request->input('attribute');
    }
    if($request->has('sort'))
    {
      $filters['sort'] = $request->input('sort');
    }
    else
    {
      // сортировка по умолчанию
      if($request->has('tag_id')) {
        $filters['tag_id'] = $request->input('tag_id');
      }
    }
    $id = $request->input('category_id');
    if($id) {
      session()->forget('filters.product.'.$id);
      session()->put('filters.product.'.$id, $filters);
    }
//    $response = array(
//      'reload' => 1
//    );
    //return response()->json($response);
  }

  public function filteredProducts($category_id) {
    $session = session()->get('filters.product.'.$category_id);
    //фильтр категории - получаем связанные товары из категории
    if($category_id) {
      $category = Category::with(['parent', 'children_rec'])->findOrFail($category_id);
      $category_ids = $category->hasChildren ? $category->children_ids($category, collect([])) : $category->id;

      $products = Product::join('category_product','products.id','category_product.product_id')
          ->whereIn('category_product.category_id', collect($category_ids))
          ->published()
          ->with('attributes');
      $this->totalProductsCount = Product::join('category_product','products.id','category_product.product_id')
          ->whereIn('category_product.category_id', collect($category_ids))
          ->published()->count();
    }else if(isset($session['tag_id'])) {
      $products = Tag::findOrFail($session['tag_id'])
          ->productsWithoutSort()
          ->published()
          ->with('attributes');
      $this->totalProductsCount = Tag::findOrFail($session['tag_id'])->published()->count();
      //по умолчанию просто фильтруем товары
    } else {
      $products = Product::with('attributes')->published();
      $this->totalProductsCount = Product::published()->count();
    }
    //фильтр по бренду
    if(isset($session['brand_id'])) {
      $products->where('brand_id', intval($session['brand_id']));
    }
    //фильтр по акции
    if(isset($session['act'])) {
      $products->where('act', 1);
    }
    //фильтр по Новинкам
    if(isset($session['new'])) {
      $products->where('new', 1);
    }
    //фильтр по Хитам
    if(isset($session['hit'])) {
      $products->where('hit', 1);
    }
    //фильтр по ценам от - до
    if(isset($session['startPrice'])) {
      $products->where('price', '>=', intval($session['startPrice']));
    }
    if(isset($session['endPrice'])) {
      $products->where('price', '<=', intval($session['endPrice']));
    }
    //фильтры по атрибутам
    if (isset($session['attributes'])) {
      foreach($session['attributes'] as $attribute_id => $value) {
        if($value) {
          $products->whereHas('attributes', function ($query) use ($value, $attribute_id) {
            if(is_array($value))
            {
              $query->where('attribute_id', $attribute_id)->where(function($query) use($value, $attribute_id) {
                foreach($value as $key => $item)
                {
                  if( $key == 0 )
                  {
                    $query->where('value', 'like', '%' . $item . '%');
                  }
                  else
                  {
                    $query->orWhere('value', 'like', '%' . $item . '%');
                  }
                }
              });
            }
            else
            {
              $query->where('attribute_id', $attribute_id)->where('value', $value);
            }

          });
        }
      }
    }

    if(isset($session['sort'])) {
      switch($session['sort'])
      {
        // сначала дороже
        case 'expensive':
          $products->orderBy('price', 'desc');
          break;
        // сначала дешевле
        case 'cheaper':
          $products->orderBy('price', 'asc');
          break;
        // сначала популярные товары
        case 'hit':
          $products->orderBy('hit', 'desc')->orderBy('name');
          break;
        // сначала акционные товары
        case 'act':
          $products->orderBy('act', 'desc')->orderBy('name');
          break;
        // сначала новинки
        case 'new':
          $products->orderBy('new', 'desc')->orderBy('name');
          break;
        // по названию товара
        case 'name':
          $products->orderBy('name');
          break;
        // по ручной сортировке в категории или теге
        case 'sort':
        default:
          if(isset($session['tag_id']))
            $products->orderBy('product_tag.sort');
          else
            $products->orderBy('category_product.sort');
      }
    }
    else
    {
      // сортировка по умолчанию
      if(isset($session['tag_id']))
        $products->orderBy('product_tag.sort');
      else
        $products->orderBy('category_product.sort');
    }

    if($session['page'] == 'all') {
      $perPage = 1000;
    }
    else {
      $perPage = Setting::getVar('perpage') ?: $this->perpage;
    }
    if(isset($session['pageCount'])) {
      $perPage *= $session['pageCount'];
    }
    $products = $products->paginate($perPage);
    return $products;
  }
  public function getFilters($category, $products, $totalProductCount = null) {
    $productsCount = $totalProductCount;
    $filters = session()->get('filters.product.'.$category->id);
    if(!$filters)
      $filters = array();
    $filters['minPrice'] = $products->min_price?:0;
    $filters['maxPrice'] = $products->max_price?:0;
    $filters['productsCount'] = $productsCount;
    if(!isset($filters['startPrice']))
      $filters['startPrice'] = $filters['minPrice'];
    if(!isset($filters['endPrice']))
      $filters['endPrice'] = $filters['maxPrice'];
    return $filters;
  }
  /**
   * Страница каталога - товары из каталога | категории из каталога
   * @param $sysname
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function catalog(Request $request, $sysname) {
//        Cache::flush();
    $this->saveFilters($request);
    $hash = md5($sysname);
    $category = Cache::remember('category' . $hash, 1440, function() use($sysname)
    {
      return Category::with([
          'parent',
          'children_rec',
          'products.attributes' => function ($query) {
            $query->where('attributes.is_filter', 1);
          }
      ])
          ->sysname($sysname)
          ->published()
          ->firstOrFail();
    });

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
      if(!session()->has('filters.product.'.$category->id)) {
        $products = Cache::remember('category.' . $hash . '.products', 60, function () use ($category_ids, $perPage) {
          return Product::with(['attributes', 'comments' => function ($query) {
            $query->average();
          }])
              ->whereHas('categories',
                  function ($query) use ($category_ids) {
                    $query->whereIn('categories.id', $category_ids);
                  })
              ->published()
              ->paginate($perPage);
        });
        $this->totalProductsCount = Product::whereHas('categories',
            function ($query) use ($category_ids) {
              $query->whereIn('categories.id', $category_ids);
            })
            ->published()->count();
      }else {
        $products = $this->filteredProducts($category->id);
      }
      $products->min_price = Cache::remember('category.'.$hash.'.products.min_price', 60, function() use($category_ids)
      {
        return Product::whereHas('categories',
            function($query) use($category_ids) {
              $query->whereIn('categories.id', $category_ids);
            })
            ->published()
            ->min('price');
      });

      $products->max_price = Cache::remember('category.'.$hash.'.products.max_price', 60, function() use($category_ids)
      {
        return Product::whereHas('categories',
            function($query) use($category_ids) {
              $query->whereIn('categories.id', $category_ids);
            })
            ->published()
            ->max('price');
      });

      // 2017.05.30 TODO: проверить нужен ли запрос ниже
      // All category products with attributes
//            $category->products = Product::with(['attributes' => function($query) {
//                    $query->where('attributes.is_filter', 1)->withPivot('value')->select('attributes.*', 'attribute_product.value');
//                }])
//                ->whereHas('categories',
//                    function($query) use($category_ids) {
//                        $query->whereIn('categories.id', $category_ids);
//                    })
//                ->select('id')
//                ->published()
//                ->get();
      $filters = $this->getFilters($category, $products, $this->totalProductsCount);
      return view('catalog.catalog', compact('category', 'products', 'banners', 'parent_zero_id', 'filters'));
    } else {

      $banners = Cache::remember('category.'.$hash.'.banners', 60, function()
      {
        return Banner::where('type', 'content')->where('status', 1)->get();
      });
      if(!session()->has('filters.product.'.$category->id)) {
        $products = Cache::remember('category.'.$hash.'.products', 60, function() use($category, $perPage)
        {
          return $category
              ->products()
              ->with(['attributes', 'comments' => function($query){
                $query->average();
              }])
              ->published()
              ->paginate($perPage);
        });


      }else {
        $products = $this->filteredProducts($category->id);
      }
      $products->min_price = Cache::remember('category.'.$hash.'.products.min_price', 60, function() use($category)
      {
        $q = $category->products()->published()->min('price');
        return $q;
      });
      $products->max_price = Cache::remember('category.'.$hash.'.products.max_price', 60, function() use($category)
      {
        return $category->products()->published()->max('price');
      });
      $filters = $this->getFilters($category, $products, $category->products()->published()->count());
      return view('catalog.catalog', compact('category', 'products', 'banners', 'parent_zero_id', 'filters'));
    }
  }

  /**
   * Страница тэгов - товары с этим тегом
   * @param $sysname
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function tags($sysname)
  {
    $tag = Tag::where('sysname', $sysname)
        ->published()
        ->firstOrFail();

//        with(['products' => function($query) {
//            $query->paginate(20); // 20 per page
//        }, 'articles' => function($query) {
//            $query->published()->paginate(20); // 20 per page
//        }])

    // if has products then render catalog, else articles
    // TODO: create pagination function for products and articles
    $products = $tag->products()->published()->paginate(20);
    if($products->count())
    {
      $products->min_price = $tag->products()->published()->min('price');
      $products->max_price = $tag->products()->published()->max('price');

      $this->setMetaTags(null, $tag->title, $tag->description, $tag->keywords);
      return view('catalog.catalog', compact('tag', 'products'));
    }
    else
    {
      $articles = $tag->articles()->published()->paginate(12);
      return view('articles.index', compact('tag', 'articles'));
    }
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
    $page = $request->input('page', null);
    $per_page = $page == 1 ? 400 : 5;
    if($request->has('text') && $request->input('text') !='') {
      $products = Product::where('name','LIKE' , '%'.$request->input('text').'%')
          ->published()
          ->orderBy('name')
          ->paginate($per_page);
    }

    if($request->isXmlHttpRequest()) {
      $nextPage = ($products->currentPage() == $products->lastPage()) ? false : $products->currentPage() + 1;
      return response()->json([
          'html' => view('catalog.products.list', compact('products'))->render(),
          'nextPage' => $nextPage,
          'total' => $products->total(),
          'count' => $products->total() - $products->currentPage() * $products->perPage(),
          'action' => $page == 1 ? 'paginationReplace' : 'paginationAppend',
          'model' => 'search',
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
  public function product($sysname)
  {
    $hash = md5($sysname);
    $product = Cache::remember('product.'.$hash, 60, function() use($sysname) {
      return Product::with([
          'brand',
          'categories',
          'photos',
          'attributes',
          'kits.products.attributes',
          'related.attributes',
          'comments' => function($query){
            $query->average();
          }
      ])->where('sysname', $sysname)->where('status', 1)->firstOrFail();
    });
    $comments = Cache::remember('product.'.$hash.'.comments', 60, function() use($product){
      return $product->comments()->published()->paginate(5);
    });
    $this->setMetaTags(null, $product->title, $product->description, $product->keywords);

    // Get youtube images
    if ($product->video_url)
    {
      $temp = null;
      if (strpos($product->video_url, '?v=')) {
        $temp = explode('?v=', $product->video_url);
      }
      else
      {
        $temp = explode('/', $product->video_url);
      }
      $product->video_code = $temp ? end($temp) : null;
    }

    //добавляем товар в просмотренные
    if(!session()->has('products.view.'.$product->id)) {
      session()->put('products.view.' . $product->id, 1);
    }
    //аналоги
    $analogues = Cache::remember('product.'.$hash.'.analogues', 60, function() use($product){
      return Product::where('id', '!=', $product->id)->whereHas('categories', function ($query) use ($product) {
        $query->whereIn('category_id', $product->categories->pluck('id')->toArray());
      })->inRandomOrder()->take(10)->get();
    });

    return view('catalog.products.details', [
        'product' => $product,
        'analogues' => $analogues,
        'comments' => $comments,
    ]);
  }



}
