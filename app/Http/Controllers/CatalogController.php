<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Banner;
use App\Models\CategoryProduct;
use App\Models\ProductComment;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Lang;
use Illuminate\Support\Facades\Cache;
use App\Models\Look;

class CatalogController extends Controller
{
    /**
     * Кол-во товаров на первом скрине, на страницах с фильтрацией
     * @var int
     */
    private $perpage = 23;
    //products count for current cutegory without filters
    public function __construct() {
        $perpage = Setting::getVar('perpage') ?: $this->perpage;
        if($perpage) {
            $this->perpage = $perpage;
        }
    }
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
    /*
     * Reset session pages for all products
     * */
    public function clearProductsPages() {
        $data = session()->get('filters.product');
        if(!isset($data))
            return;
        foreach($data as $key => $category) {
            if(in_array($key, ['new', 'act', 'hit'])) {
                foreach($category as $pKey =>  $pCategory) {
                    if(isset($pCategory['page'])) {
                        $way = 'filters.product.'.$key.'.'.$pKey;
                        session()->put($way.'.page', 1);
                        session()->put($way.'.pageCount', 1);

                    }
                }
            }else {
                if(isset($category['page'])) {
                    $way = 'filters.product.'.$key;
                    session()->put($way.'.page', 1);
                    session()->put($way.'.pageCount', 1);
                }
            }
        }
    }
    public function saveFilters(Request $request, $postfix = '') {
        //dd(session()->all());
        $this->clearProductsPages();
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
        if(!$id) {
            $id = $request->input('tag_id');
        }
        if($id) {
            session()->forget('filters.product.'.$postfix.$id);
            session()->put('filters.product.'.$postfix.$id, $filters);
        }
    }
    public function filteredProducts($category_id, $postfix = '') {
        //фильтр категории - получаем связанные товары из категории
        if($category_id) {
            $session = session()->get('filters.product.'.$postfix.$category_id);
            $category = Category::with(['parent', 'children_rec'])->findOrFail($category_id);
            //добавил выбор id по первой таблице(products.id), так как он затирался id из второй таблицы и соответственно товар получал чужие аттрибуты
            $products = Product::inCategory($category);
        } elseif(session()->has('tag_id')) {//товары из тегов
            $tag_id = session()->get('tag_id');
            $session = session()->get('filters.product.'.$postfix.$tag_id);
            $tag = Tag::findOrFail($tag_id);
            $products = Product::inTag($tag);
            //по умолчанию просто фильтруем товары
        } else {
            $products = Product::withInfo();
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
                    $products->orderBy('hit', 'desc')->orderBy('hit_sort', 'asc');
                    break;
                // сначала акционные товары
                case 'act':
                    $products->orderBy('act', 'desc')->orderBy('act_sort', 'asc');
                    break;
                // сначала новинки
                case 'new':
                    $products->orderBy('new', 'desc')->orderBy('new_sort', 'asc');
                    break;
                // по названию товара
                case 'name':
                    $products->orderBy('name');
                    break;
                // по ручной сортировке в категории или теге
                case 'sort':
                default:
                    if(isset($tag_id)) {
                        $products->orderBy('product_tag.sort');
                    }
                    else {
                        $products->orderBy('sort', 'asc');
                        //$products->orderBy('id');
                    }
            }
        }
        else
        {
            // сортировка по умолчанию
            if(isset($session['tag_id'])) {
                $products->orderBy('product_tag.sort');
            }
            else {
                $products->orderBy('sort', 'asc');
            }
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
        $products = $products->distinctPaginate($perPage);
        return $products;
    }
    /*
     *
     * **/
    public function getFilters($category, $products, $postfix = '') {
        if(isset($category)) {
            $filters = session()->get('filters.product.'.$postfix.$category->id);
        }elseif(session()->has('tag_id')) {
            $tag_id = session()->get('tag_id');
            $filters = session()->get('filters.product.'.$postfix.$tag_id);
        }

        if(!isset($filters))
            $filters = array(
                'sort' => 'sort'
            );
        $filters['minPrice'] = $products->min_price?:0;
        $filters['maxPrice'] = $products->max_price?:0;
        $filters['productsCount'] = $products->totalCount;
        if(!isset($filters['startPrice']))
            $filters['startPrice'] = $filters['minPrice'];
        if(!isset($filters['endPrice']))
            $filters['endPrice'] = $filters['maxPrice'];
        return $filters;
    }
    /*
     * Сначала ищет категорию в кэше, если не находит, то берет из базы
     * дополнительно берется родительская категория, аттрибуты продуктов
     * todo:сделать кэширование с учетом филтров, для ускорения работы сайта
     * **/
    public function getCategory($sysname, $hash = null) {

        //кэш пока не используется, так как используются фильтры и постраничная навигация
//        if($hash == null) {
//            $hash = md5($sysname);
//        }
        return Category::with([
            'parent',
            'children_rec',
            'products.attributes' => function ($query) {
                $query->where('attributes.is_filter', 1);
            },
            'offers',
        ])->sysname($sysname)->published()->firstOrFail();

    }
    /*
     * Дописываем к товару дополнительную информацию:
     * page - общее количество товаров
     * **/
    private function addProductsInfo($category, &$products, $request) {
        $products->page = $request->input('pageCount', 1);
        $lastOnPrevPage = ($products->page - 1) * $category->perpage;
        //помечаем товар, который был последним на предыдущей странице, до него будет прокрутка
        if($products->page > 1 && isset($products[$lastOnPrevPage])) {
            $products[$lastOnPrevPage]->lastOnPrevPage = true;
        }
    }
    /*
     * Берет продукты из базы, для заданной категории с учетом филтров
     * todo:добавить кэширование с учетом фильтров
     * **/
    public function getCategoryProducts($category, $request = null, $sysname = null, $hash = null) {
//        if($hash == null) {
//            $hash = md5($sysname);
//        }
        if(!$request) {
            $request = new Request();
        }
        $category_ids = $category->children_ids($category, collect([]));
        if(!session()->has('filters.product.'.$category->id)) {
            $products = Product::inCategory($category)->orderBy('sort')->distinctPaginate($category->perpage);
        } else {
            $products = $this->filteredProducts($category->id);
        }
        $products->min_price = Product::whereHas(
            'categories',
            function($query) use($category_ids) {
                $query->whereIn('categories.id', $category_ids);
            })->published()->min('price');
        $products->max_price = Product::whereHas(
            'categories',
            function($query) use($category_ids) {
                $query->whereIn('categories.id', $category_ids);
            })->published()->max('price');
        $this->addProductsInfo($category, $products, $request);
        return $products;
    }
    /**
     * Страница каталога - товары из каталога | категории из каталога
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function catalog(Request $request, $sysname) {
        $this->saveFilters($request);
        $hash = md5($sysname);
        $category = $this->getCategory($sysname, $hash);
        $this->setMetaTags(null, $category->title, $category->description, $category->keywords);

        //todo:это надо убрать - переместить переменную в коллекцию и переделать соответствующие виды
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
        $category->perpage = intval(Setting::getVar('perpage')) ?: $this->perpage;
        $products = $this->getCategoryProducts($category, $request);
        $filters = $this->getFilters($category, $products);
        $sizesData = $this->getSizesData();
        // Если категория родительская, то баннеров не будет
        if(!$category->hasChildren) {
            $banners = Cache::remember('category.'.$hash.'.banners', 60, function()
            {
                return Banner::where('type', 'content')->where('status', 1)->get();
            });
        }
        return view('catalog.catalog', compact('category', 'products', 'banners', 'parent_zero_id', 'filters', 'sizesData'));
    }
    /*
     * todo:от этой функции нужно избавиться - остался костыль после многочисленного переделывания вывода размеров
     * @return array with
     * man_sizes, womanSizes, manCategoryId, womanCategoryId
     * **/
    public function getSizesData() {
        $sizesData = array();
        return $sizesData;
    }
    /**
     * Страница тэгов - товары с этим тегом
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tags(Request $request, $sysname)
    {
        $tag = Tag::where('sysname', $sysname)
            ->published()
            ->firstOrFail();

        // if has products then render catalog, else articles
        $postfix = 'tag.';
        $request->request->add(['tag_id' => $tag->id]);
        $this->saveFilters($request, $postfix);
        $perpage = Setting::getVar('perpage') ?: $this->perpage;
        if(!session()->has('filters.product.'.$postfix.$tag->id)) {
            $products = Product::inTag($tag)->distinctPaginate($perpage);

        }else {
            session()->put('tag_id', $tag->id);
            $products = $this->filteredProducts(null, $postfix);
        }
        if($products->count())
        {
            $products->min_price = $tag->products()->published()->min('price');
            $products->max_price = $tag->products()->published()->max('price');

            $this->setMetaTags(null, $tag->title, $tag->description, $tag->keywords);
            $sizesData = $this->getSizesData();
            $filters = $this->getFilters(null, $products, $postfix);
            $fakeCategory = collect();
            $fakeCategory->perpage = intval(Setting::getVar('perpage')) ?: $this->perpage;
            $this->addProductsInfo($fakeCategory, $products, $request);
            return view('catalog.catalog', compact('tag', 'products', 'sizesData', 'filters'));
        }
        else
        {
            $articles = $tag->articles()->published()->paginate(12);
            return view('articles.index', compact('tag', 'articles'));
        }
    }
    /*
     * Возвращает представление товаров по заданному полю например хиты(hit) или акции(act)
     * **/
    public function getProductsByField($field, $request, $sysname = null) {
        $postfix = $field.'.';
        $perpage = Setting::getVar('perpage') ?: $this->perpage;
        if($sysname)
        {
            $category = Category::with('children_rec')->sysname($sysname)->firstOrFail();
            $category->perpage = $perpage;
            $request->request->add([$field => '1', 'category_id' => $category->id]);
            $this->saveFilters($request, $postfix);
            if(!session()->has('filters.product.'.$postfix.$category->id)) {
                $products = Product::inCategory($category)->where($field, 1)->orderBy($field.'_sort')->distinctPaginate($category->perpage);
            }else {
                $products = $this->filteredProducts($category->id, $postfix);
            }
        }
        else
        {
            $products = Product::with('attributes')->where($field, 1)->where('status', 1)->orderBy('name');
        }
        //если категория общая, то категории не нужны, берем все товары, например все новинки
        if(!isset($category)) {
            $products = Product::withInfo()->where($field, 1)->orderBy('id')->distinctPaginate($perpage);
        }
        $products->min_price = Product::where($field, 1)->where('status', 1)->min('price');
        $products->max_price = Product::where($field, 1)->where('status', 1)->max('price');
        $page = $this->setMetaTags();
        if($page && isset($category))
        {
            $page->category = $category;
            $page->sysname = $category->sysname;
        }
        $sizesData = $this->getSizesData();
        if(isset($category)) {
            $filters = $this->getFilters($category, $products, $postfix);
            $this->addProductsInfo($category, $products, $request);
        }else {
            $fakeCategory = collect();
            $fakeCategory->perpage = intval(Setting::getVar('perpage')) ?: $this->perpage;
            $this->addProductsInfo($fakeCategory, $products, $request);
        }
        return view('catalog.catalog', compact('products', 'page', 'category', 'filters', 'sizesData'));
    }
    /**
     * Страница акции - товары с ярлыком "Акция"
     * @param $sysname - чпу родительской категории
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actions(Request $request, $sysname = null) {
        $field = 'act';
        return $this->getProductsByField($field, $request, $sysname);
    }
    /**
     * Страница выбор покупателей - товары с ярлыком "Хит"
     * @param $sysname - ЧПУ родительской категории
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hits(Request $request, $sysname = null) {
        $field = 'hit';
        return $this->getProductsByField($field, $request, $sysname);
    }
    /**
     * Страница новинки - товары с ярлыком "Новинка"
     * @param $sysname - ЧПУ родительской категории
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newProducts(Request $request, $sysname = null) {
        $field = 'new';
        return $this->getProductsByField($field, $request, $sysname);
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
     * Результаты поиска
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request) {
        //TODO: переделать на полнотекстовой через эластиксеарч
        $page = $request->input('page', null);
        $per_page = $page == 1 ? 400 : Setting::getVar('perpage') ?: $this->perpage;
        if($request->has('text') && $request->input('text') !='') {
//            $products = Product::where('name','LIKE' , '%'.$request->input('text').'%')
//                ->published()->with('attributes')
//                ->orderBy('name')
//                ->paginate($per_page);
            $products = Product::where('name','LIKE' , '%'.$request->input('text').'%')
                ->orderBy('name')->withInfo()->distinctPaginate($this->perpage);
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
            $this->setMetaTags(null, 'Результаты поиска');
            return view('catalog.search', [
                'products' => !empty($products) ? $products : null,
                'text' => $request->input('text'),
                'sizesData' => $this->getSizesData(),
            ]);
        }
    }

    /**
     * Страница отложенных товаров
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookmarks() {
        $products = collect();
        $perpage = 48;
        if(session()->has('products.defer') && $defers = session()->get('products.defer')) {
            //выводим последние 48 товара
            arsort($defers);
            $defers = array_slice(array_keys($defers), 0, 48);
//            $products = Product::with('attributes')->whereIn('id', $defers)->where('status', 1)->orderByRaw('FIELD(id, '.implode(',', $defers).')')->take(48)->get();
            $products = Product::whereIn('id', $defers)->orderByRaw('FIELD(id, '.implode(',', $defers).')')->withInfo()->distinctPaginate($perpage);
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
        $perpage = 48;
        if(session()->has('products.view')) {
            //выводим последние 48 товара
            $views = session()->get('products.view');
            arsort($views);
            $views = array_slice(array_keys($views), 0, 48);
//            $products = Product::with('attributes')->whereIn('id', $views)->where('status', 1)->orderByRaw('FIELD(id, '.implode(',', $views).')')->take(48)->get();
            $products = Product::whereIn('id', $views)->orderByRaw('FIELD(id, '.implode(',', $views).')')->withInfo()->distinctPaginate($perpage);

        }
        $this->setMetaTags();
        return view('catalog.seen', compact('products'));
    }
    /*
     * Возвращает информации о категории, если категория не задана, узнает ее по продукту
     * где поле crumbs содержит все chpu подкатегорий, crumbs[0] содержит chpu главной категории, crumbs[n] - соответственно chpu заданной категории
     * @return array
     * **/
    public function getCategoryInfo($category, $product) {
        $crumbs = array();
        if(!$category) {
            $category = Category::where('id', $product->categories[0]->id)->firstOrFail();
        }
        while($category->parent_id > 0) {
            $crumbs[] = $category->sysname;
            $category = Category::where('id', $category->parent_id)->firstOrFail();
        }
        $crumbs[] = $category->sysname;
        $result = array(
            'crumbs' => array_reverse($crumbs),
        );
        return $result;
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
                },
            ])->where('sysname', $sysname)->where('status', 1)->firstOrFail();
        });
        $comments = Cache::remember('product.'.$hash.'.comments', 60, function() use($product){
            return $product->comments()->published()->orderBy('created_at', 'desc')->paginate(5);
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
        foreach($product->attributes as $attribute) {
            if(isset($attribute->pivot->value, $attribute->sysname)) {
                $sysname = $attribute->sysname;
                $product->$sysname = $attribute->pivot->value;
            }
        }

        $looks = $product->looks()->published()->with('products', 'products.attributes')->get();

        return view('catalog.products.details', [
            'product' => $product,
            'analogues' => $analogues,
            'comments' => $comments,
            'sizesData' => $this->getSizesData(),
            'looks' => $looks,
        ]);
    }


    public function main_category(Request $request)
    {
        $sysname = $request->path();
        $category = Category::where('sysname', $sysname)->published()->firstOrFail();
        $this->setMetaTags(null, $category->title, $category->description, $category->keywords);
        $categories = $category->children()->with('children')->published()->get();

        return view('catalog.' . $sysname, compact('category', 'categories'));
    }



}
