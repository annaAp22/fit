<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

use Illuminate\Contracts\Mail\Mailer;
use App\Http\Requests;
use App\Models\Page;
use \App\Models\Product;
use Validator;
use Carbon\Carbon;
use DB;
use Mail;

class MainController extends Controller
{
    /**
     * Главная страница
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        //кол-во товаров на сайте
        $cnt_products = Product::count();
        //банер в тексте
        $banner_content = \App\Models\Banner::where('type', 'content')->where('status', 1)->first();
        //бренды
//        $brands = \App\Models\Brand::where('status', 1)->get();
        //статьи

        //товары - выбор покупателей
//        $hit_products = Product::where('hit', 1)->where('status', 1)->orderBy('id', 'desc')->take(10)->get();

        //самый покупаемый товар за последнюю неделю
//        $order_product = DB::table('order_product as og')
//            ->select('*', DB::raw('COUNT(*) as total_product'))
//            ->join('products as g', 'og.product_id', '=', 'g.id')
//            ->where('og.created_at', '>=', Carbon::now()->subWeek())
//            ->whereNull('g.deleted_at')
//            ->where('g.status', 1)
//            ->groupBy('product_id')->orderBy('total_product', 'desc')->first();
//        if($order_product) {
//            $product_week = Product::find($order_product->id);
//        }

        //банеры в слайдере
        $banners = \App\Models\Banner::where('type', 'main')->where('status', 1)->get();

        // Товары
//        $hit_products = Product::with('attributes')->published()->hit()->recentlyAdded()->take(10)->get();
//        $new_products = Product::with('attributes')->published()->new()->recentlyAdded()->take(10)->get();
//        $act_products = Product::with('attributes')->published()->act()->recentlyAdded()->take(10)->get();

        //родительские категории
        $categories = \App\Models\Category::with(['children.products', 'products'])
            ->where(function($query){
                $query->where('parent_id', 0)
                    ->orWhere('parent_id', null);
            })
            ->where('status', 1)
            ->orderBy('sort')
            ->get();

//        $reviews = \App\Models\Review::where('status', 1)->orderBy('created_at', 'desc')->take(10)->get();


        $this->setMetaTags();
        return view('content.index', [
            'banner_content' => $banner_content,
            'cnt_products' => $cnt_products,

            'banners' => $banners,

//            'hit_products' => $hit_products,
//            'new_products' => $new_products,
//            'act_products' => $act_products,

            'categories' => $categories,

//            'reviews' => $reviews,

//            'brands' => $brands,
//            'product_week' => !empty($product_week) ? $product_week : null
        ]);
    }

    /**
     * Статичная html - страница
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function page(Request $request, $sysname) {
//        $sysname = substr($request->path(), 0, (strpos($request->path(), '.') ?: 1000));
        $page = Page::where('sysname', $sysname)->with(['vars', 'photos'])->firstOrFail();
        $this->setMetaTags();

        return view('content.with_sidebar', ['page' => $page]);
    }

    /**
     * Страница сертификатов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sertificates() {
        $sertificates = \App\Models\Sertificate::orderBy('id','desc')->get();
        $page = Page::where('sysname', 'sertificates')->with('vars')->firstOrFail();
        $this->setMetaTags();

        return view('content.sertificates', ['page' => $page, 'sertificates' => $sertificates]);
    }

    /**
     * Страница Доставки и оплаты
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delivery() {
        $page = Page::where('sysname', 'delivery')->with('vars')->firstOrFail();
        $this->setMetaTags();
        return view('content.with_sidebar', ['page' => $page]);
    }

    /**
     * Страница Гарантия и возврат
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warranty() {
        $page = Page::where('sysname', 'warranty')->with('vars')->firstOrFail();
        $this->setMetaTags();
        return view('content.content', ['page' => $page]);
    }

    /**
     * Страница Самовывоз
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pickup() {
        $page = Page::where('sysname', 'pickup')->with('vars')->firstOrFail();
        $this->setMetaTags();
        return view('content.content', ['page' => $page]);
    }

    /**
     * Страница контактов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contacts() {
        $page = Page::where('sysname', 'contacts')->with('vars', 'photos')->firstOrFail();
        $this->setMetaTags();
        return view('content.contacts', ['page' => $page]);
    }

    /**
     * Страница Полезно знать
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articles() {
        $articles = \App\Models\Article::where('status', 1)->orderBy('date', 'desc')->paginate(10);
        $this->setMetaTags();
        return view('articles.index', ['articles' => $articles]);
    }

    /**
     * Страница Статьи
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function article($sysname) {
        $article = \App\Models\Article::where('sysname', $sysname)->where('status', 1)->firstOrFail();
        $this->setMetaTags(null, $article->title, $article->description, $article->keywords);

        //товары по теме, исходя из тэгов статьи
        if($article->tags()->count()) {
            // REALLY NEED THIS?????????????????????
            //$products = \App\Models\Product::whereHas('tags', function ($query) use ($article) {
            //    $query->whereIn('tag_id', [$article->tags->implode('id', ', ')]);
            //})->where('status', 1)->orderBy('id', 'desc')->take(100)->get();
            $relatedArticles = \App\Models\Article::whereHas('tags', function($query) use ($article) {
                $query->whereIn('tag_id', [$article->tags->implode('id', ', ')]);
            })
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->take(25)
                ->paginate(3);
        }

        return view('articles.inner', [
            'article'         => $article,
            'relatedArticles' => isset($relatedArticles) ? $relatedArticles : null,
            //'products' => !empty($products) ? $products : null
        ]);
    }

    /**
     * Отзывы
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviews(Request $request) {
        $reviews = \App\Models\Review::published()->recent()->paginate(10);

        return view('reviews.list', compact('reviews'));
    }

    /**
     * Листинг новостей
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function news(Request $request) {
        $news = News::published()->recent()->paginate(12);
        return view('news.index', compact('news'));
    }

    /**
     * Новость
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newsSingle(Request $request, $sysname) {
        $page = News::published()->bySysname($sysname)->first();
        if(!$page)
            abort(404);

        return view('news.detailed', compact('page'));
    }
}
