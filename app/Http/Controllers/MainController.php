<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\News;
use Illuminate\Http\Request;

use Illuminate\Contracts\Mail\Mailer;
use App\Http\Requests;
use App\Models\Page;
use \App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Validator;
use App\Helpers;
use Carbon\Carbon;
use DB;
use Mail;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    /**
     * Главная страница
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
      //стираем куки фильтров, на случай непредвиденных глюков
      if(session()->has('filters')) {
        session()->forget('filters');
      }
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
        $redirect = array(
            'agencies' => 'agencies',
            'delivery' => 'delivery',
            'contacts' => 'contacts',
            'reviews' => 'reviews',
            'otzyvy' => 'reviews',
        );
        if(isset($redirect[$sysname])) {
          return redirect()->route($redirect[$sysname]);
        }
        $query = Page::where('sysname', $sysname)->with(['vars', 'photos']);
        if($request->isXmlHttpRequest()) {
            $page = $query->firstOrNew([]);
        } else {
            $page = $query->published()->firstOrFail();
        }
        //некоторые фото могут содержаться на разных страницах, например контакты и наш магазин имеют одинаковые фото
        //в параметре add_photos можно указать sysname страницы, с которой взять фотки
        $add_photos = $page->vars->where('var', 'add_photos')->first();
        if($add_photos) {
            $other_page = Page::where('sysname', $add_photos['value'])->with(['vars', 'photos'])->firstOrFail();
            foreach($other_page->photos as $photo) {
                $page->photos->push($photo);
            }
        }
// Replace <!--{{block_name}}--> with rendered value
        $vars = [];
        $template_vars = $page->vars->where('var', 'template_vars')->first();
        if($template_vars)
        {
            foreach(explode(',', $template_vars['value']) as $template)
            {
                if (View::exists($template)) {
                    $vars[$template] = view($template, compact('page'))->render();
                } else {
                    Log::warning('view '.$template.' not found in page '.$page->sysname);
                }
            }

            $page->content = Helpers\process_vars($page->content, $vars);
        }
        if($request->isXmlHttpRequest()) {
            //текс нужно добавить в блок с постфиксом sysname
            return response()->json([
                'text' => [
                    '#js-'.$sysname => $page->content,
                ],
                'action' => 'elementsRender',
            ]);
        } else {
            $this->setMetaTags(null, $page->title, $page->description, $page->keywords);
            return view('content.with_sidebar', ['page' => $page]);
        }
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
    public function delivery(Request $request) {
        $query = Page::where('sysname', 'delivery')->with('vars');
        if($request->isXmlHttpRequest()) {
            $page = $query->firstOrNew(['content' => '']);
            return response()->json([
                'text' => [
                    '#js-delivery-wrapper' => $page->content,
                ],
                'action' => 'elementsRender',
            ]);

        }else {
            $page = $query->published()->firstOrFail();
            $this->setMetaTags(null, $page->title, $page->description, $page->keywords);
            return view('content.with_sidebar', ['page' => $page]);
        }
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
        $this->setMetaTags(null, $page->title, $page->description, $page->keywords);
        return view('content.contacts', ['page' => $page]);
    }
    /**
     * Страница Полезно знать
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articles() {
        $page = Page::where('sysname', 'articles')->first();
        if(!$page) {
            $page = new Page();
            $page->name = 'Рецепты';
            $page->content = '';
        }
        $articles = Article::where('status', 1)->orderBy('date', 'desc')->paginate(12);
        $this->setMetaTags();
        return view('articles.index', ['page' => $page, 'articles' => $articles]);
    }

    /**
     * Страница Статьи
     * @param $sysname
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function article($sysname) {
        $article = Article::where('sysname', $sysname)
            ->with('tags')
            ->published()
            ->firstOrFail();
        $this->setMetaTags(null, $article->title, $article->description, $article->keywords);


        if($article->tags->count()) {

            $relatedArticles = Article::whereHas('tags', function($query) use ($article) {
                $query->whereIn('tag_id', [$article->tags->implode('id', ', ')]);
            })
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->take(25)
                ->paginate(3);
        }

        return view('articles.details', [
            'page'         => $article,
            'articles' => isset($relatedArticles) ? $relatedArticles : null,
            //'products' => !empty($products) ? $products : null
        ]);
    }

    public function tagArticle($tag_sysname, $sysname)
    {
        $hash = md5($sysname);
        $page = Cache::remember('article.'.$hash, 60, function() use($sysname) {
            return Article::published()
                ->where('sysname', $sysname)
                ->firstOrFail();
        });


        $this->setMetaTags(null, $page->title, $page->description, $page->keywords);

        $hash = md5($tag_sysname);
        $tag = Cache::remember('article.related.'.$hash, 60, function() use($tag_sysname, $page) {
            return Tag::published()
                ->where('sysname', $tag_sysname)
                ->with(['articles' => function($query) use($page) {
                    $query->published()
                        ->recent()
                        ->where('articles.id', '!=', $page->id)
                        ->take(8);

                }])
                ->firstOrFail();
        });
        $articles = $tag->articles;
        return view('articles.details', compact('page', 'articles', 'tag'));
    }

    /**
     * Отзывы
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviews(Request $request) {
        $reviews = \App\Models\Review::published()->recent()->paginate(10);
        $this->setMetaTags();
        return view('reviews.list', compact('reviews'));
    }

    /**
     * Листинг новостей
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function news(Request $request) {
        $news = News::published()->recent()->paginate(12);
        $this->setMetaTags();
        return view('news.index', compact('news'));
    }

    /**
     * Новость
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newsSingle(Request $request, $sysname) {
        $page = News::published()->bySysname($sysname)->first();
        $this->setMetaTags();
        if(!$page)
            abort(404);

        return view('news.detailed', compact('page'));
    }
}
