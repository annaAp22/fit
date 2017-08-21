<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\MsAgent;
use App\Models\MsOrder;
use App\Models\MsParam;
use App\Models\ProductComment;
use App\Models\RetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\Order;
use App\Models\News;

use Lang;

class FrontApiController extends Controller
{
    private $perpage = 20;

    /**
     * Метод подписки на рассылку
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request) {
        $validator = Validator::make($request->input(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails())
            return [
                'error' => 1,
                'message' => 'При оформлении подписки произошла ошибка. Попробуйте снова.',
            ];

        $subscribe = \App\Models\Subscriber::firstOrCreate([
            'email' => $request->input('email'),
            'act' => $request->has('act') && $request->input('act') ?
                $request->input('act') : 1
        ]);
        return [
            'action' => 'openModal',
            'modal' => view('modals.subscribe_success')->render(),
        ];
    }

    /**
     * Отправка письма администратору
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function letter(Request $request) {
        $validator = Validator::make($request->input(), [
            'email' => 'required|email',
            'name' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'При отправке письма произошла ошибка. Попробуйте снова.']);
        }

        Mail::send('emails.support.question',
            ['name' => $request->input('name'),
                'text' => $request->input('text'),
                'email' => $request->input('email')], function ($message) use ($request){
                $message->from($request->input('email'), $request->input('name'));
                $message->to(\App\Models\Setting::getVar('email_support'))->subject('Вопрос с сайта '.$request->root());
            });
        $action = $request->input('action');
        return response()->json([
            'result' => 'ok',
            'name' => $request->input('name'),
            'action' => $action,
            'action' => 'openModal',
            'modal' => view('modals.letter_success')->render(),
        ]);
    }
    /**
     * Отправка письма вопросов и предложений администратору
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function questions(Request $request) {
        $validator = Validator::make($request->input(), [
            'phone' => 'required',
            'email' => 'required|email',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'При отправке письма произошла ошибка. Попробуйте снова.']);
        }

        Mail::send('emails.support.question',
            ['phone' => $request->input('phone'),
                'text' => $request->input('text'),
                'email' => $request->input('email')], function ($message) use ($request){
                $email = \App\Models\Setting::getVar('email_support');
                $caption = 'Вопрос с сайта '.$request->root();
                $message->to($email)->subject($caption);

//          $message->from($request->input('email'), $request->input('name'));
//          $message->to(\App\Models\Setting::getVar('email_support'))->subject('Вопрос с сайта '.$request->root());
            });
        return response()->json([
            'result' => 'ok',
            'action' => 'openModal',
            'modal' => view('modals.letter_success')->render(),
        ]);
    }
    /**
     * Заказ обратного звонка
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request) {
        $validator = Validator::make($request->input(), [
            'phone' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'При запросе произошла ошибка. Попробуйте снова.']);
        }

        Mail::send('emails.support.callback',
            ['name' => $request->input('name'),
                'phone' => $request->input('phone')], function ($message) use ($request) {
                $email = \App\Models\Setting::getVar('email_support');
                $caption = 'Запрос обратного звонка '.$request->root();
                $message->to($email)->subject($caption);
            });

        return response()->json([
            'result' => 'ok',
            'action' => 'openModal',
            'modal' => view('modals.letter_success')->render()
        ]);
    }
    /**
     * Заказ обратного звонка
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cooperation(Request $request) {
        $validator = Validator::make($request->input(), [
            'email' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'При запросе произошла ошибка. Попробуйте снова.']);
        }

        Mail::send('emails.cooperation',
            ['name' => $request->input('name'),
                'email' => $request->input('email')], function ($message) use ($request) {
                $email = \App\Models\Setting::getVar('email_support');
                $caption = 'Запрос на сотрудничество '.$request->root();
                $message->to($email)->subject($caption);
            });

        return response()->json([
            'result' => 'ok',
            'action' => 'openModal',
            'modal' => view('modals.letter_success')->render()
        ]);
    }

    /**
     * AJAX - подгрузка товаров
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    public function getProducts(Request $request) {
        //фильтр категории - получаем связанные товары из категории
        if($request->has('category_id') && $request->input('category_id')) {
            $category = Category::with(['parent', 'children_rec'])->findOrFail($request->input('category_id'));
            $category_ids = $category->hasChildren ? $category->children_ids($category, collect([])) : $category->id;

            $products = Product::join('category_product','products.id','category_product.product_id')
                ->whereIn('category_product.category_id', collect($category_ids))
                ->published()
                ->with('attributes');

            //фильтр по тегу - получаем связанные товары с тэгом
        }else if($request->has('tag_id') && $request->input('tag_id')) {
            $products = Tag::findOrFail($request->input('tag_id'))
                ->productsWithoutSort()
                ->published()
                ->with('attributes');
            //по умолчанию просто фильтруем товары
        } else {
            $products = Product::with('attributes')->published();
        }
        $filters = array(
            'page' => $request->input('page'),
        );
        //фильтр по бренду
        if($request->has('brand_id') && $request->input('brand_id')) {
            $brand_id = intval($request->input('brand_id'));
            $products->where('brand_id', $brand_id);
            $filters['brand_id'] = $brand_id;
        }
        //фильтр по акции
        if($request->has('act') && $request->input('act')) {
            $products->where('act', 1);
            $filters['act'] = 1;
        }
        //фильтр по Новинкам
        if($request->has('new') && $request->input('new')) {
            $products->where('new', 1);
            $filters['new'] = 1;
        }
        //фильтр по Хитам
        if($request->has('hit') && $request->input('hit')) {
            $products->where('hit', 1);
            $filters['hit'] = 1;
        }
        //фильтр по ценам от - до
        if($request->has('price_from') && $request->input('price_from')) {
            $price_from = intval($request->input('price_from'));
            $products->where('price', '>=', $price_from);
            $filters['startPrice'] = $price_from;
        }
        if($request->has('price_to') && $request->input('price_to')) {
            $price_to = intval($request->input('price_to'));
            $products->where('price', '<=', intval($request->input('price_to')));
            $filters['endPrice'] = $price_to;
        }
        //фильтры по атрибутам
        if ($request->has('attribute')) {
            $filters['attributes'] = $request->input('attribute');
            foreach($request->input('attribute') as $attribute_id => $value) {
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
        if($request->has('sort'))
        {
            $filters['sort'] = $request->input('sort');
            switch($request->input('sort'))
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
                    if($request->has('tag_id'))
                        $products->orderBy('product_tag.sort');
                    else
                        $products->orderBy('category_product.sort');
            }
        }
        else
        {
            // сортировка по умолчанию
            if($request->has('tag_id')) {
                $products->orderBy('product_tag.sort');
                $filters['tag_id'] = $request->input('tag_id');
            }

            else
                $products->orderBy('category_product.sort');
        }

        if($request->input('page') == 'all') {
            $perPage = 1000;
        }
        else {
            $perPage = Setting::getVar('perpage') ?: $this->perpage;
        }

        $products = $products->paginate($perPage);//Setting::getVar('perpage') ?: $this->perpage, ['*'], 'page', $request->has('page') ? intval($request->input('page')) : null);

        $response['result'] = 'ok';
        //если запросили 1 страницу, то перед выводом отчищаем предыдущие результаты
        if($request->has('page') && $request->input('page') == 1) {
            $response['clear'] = true;
        }

        $response['next_page'] = ($products->lastPage() > $products->currentPage() ? ($products->currentPage() + 1) : null);
        $response['count'] = $response['next_page'] ? $products->total() - ($products->currentPage() * $products->perPage()) : 0;
        if($products->total()) {
            $response['items'] = view('catalog.products.list', ['products' => $products])->render();
        }
        else {
            $response['clear'] = true;
            $response['items'] = "<p class='header-listing'>Товаров с таким набором параметров не найдено.</p>";
        }

//            \App\Helpers\inflectByCount($products->total(), ['one' => 'товар', 'many' => 'товара', 'others' => 'товаров']);
        //save filters
        $id = $request->input('category_id');
        if($id) {
            session()->forget('filters.product.'.$id);
            session()->put('filters.product.'.$id, $filters);
        }

        return response()->json($response);
    }
    /**
     * Быстрый заказ
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fast(Request $request) {
        $data = $request->input();
        $is_multiple = $request->input('is_multiple', 0);

        $validate = [
            'name' => 'required',
            'phone' => 'required',
        ];
        if( !$is_multiple) {
            $validate['id'] = 'required|exists:products,id';
        }
        $validator = Validator::make($data, $validate);

        if ($validator->fails()) {
            return response()->json(['error' => 1, 'message' => 'При оформлении заказа произошла ошибка. Попробуйте снова.']);
        }
        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'datetime' => date('Y-m-d H:i:s'),
            'status' => 'wait',
            'email' => $request->input('email', 'no email'),
            'extra_params' => ['type' => 'one-click'],
        ];
        if(Auth::check()) {
            $data['customer_id'] = Auth::user()->id;
        }
        if($is_multiple)
        {
            $order = Order::create($data);
            $amount = 0;
            foreach(session()->get('products.cart') as $product_id => $items) {
                foreach($items as $p_size => $product ) {
                    $order->products()->attach($product_id, [
                        'cnt'          => $product['cnt'],
                        'price'        => $product['price'],
                        'extra_params' => isset($product['extra']) ? json_encode($product['extra']) : '',
                    ]);
                    $amount += $product['cnt']*$product['price'];
                }
            }
            $order->update(['amount' => $amount]);
            session()->forget('products.cart');

            $res['modalAction'] = 'refresh-on-close';
        }
        else
        {
            $product = Product::find($request->input('id'));
            $quantity = $request->input('quantity');
            $data['amount'] = $product->price * $quantity;

            $order = Order::create($data);

            $size = $request->input('size', null);
            $order->products()->attach($request->input('id'), [
                'cnt' => $quantity,
                'price' => $product->price,
                'extra_params' => $size ? json_encode(['size' => $size]) : '',
            ]);
            // Add new order to moySklad orders table
            $msOrder = new MsOrder();
            $msOrder->ms_description = json_encode([
                'order_id' => $order->id,
                'name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => null,
                'delivery' => null,
            ]);

            $positions = [];
            foreach ($order->products as $product)
            {
                $params = json_decode($product->pivot->extra_params);
                $sku = isset($params->size) ? $product->sku . "-" . $params->size : $product->sku;
                $ms_product = $product->ms_products()->where('ms_sku', $sku)->first();
                if($ms_product)
                {
                    $positions[] = [
                        "quantity" => intval($product->pivot->cnt),
                        "price" => (floatval($product->price) / (100 - $product->discount))  * 100 * 100,
                        "discount" => floatval($product->discount),
                        "vat" => 0,
                        "assortment" => [
                            "meta" => [
                                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/". $ms_product->ms_type ."/" . $ms_product->ms_uuid,
                                "type" => $ms_product->ms_type,
                                "mediaType" => "application/json"
                            ]
                        ],
                        "reserve" => floatval(MsParam::reservation()->first()->value),
                    ];
                }
            }

            // Search agent
            $phoneVariants = [
                $order->phone,
                str_replace([' ', '+'], '', $order->phone),
                str_replace([' ', '7', '+'], ['','8', ''], $order->phone ),
                str_replace([' ', '7', '+'], '', $order->phone),
            ];
            if( $agent = MsAgent::whereIn('ms_phone', $phoneVariants)
                ->orWhere(function($query) use($order){
                    $query->whereNotNull('ms_email')
                        ->where('ms_email', $order->email);
                })
                ->first() )
            {
                $msOrder->ms_agent_id = $agent->ms_uuid;
            }
            $msOrder->ms_positions = json_encode($positions);
            $msOrder->save();
        }
        //сохраняем заказ в таблицу для retailcrm
        RetailOrder::create(['order_id' => $order->id]);
        //отправка почты, может быть отключена в настройках
        if(!env('MAIL_DISABLED')) {
            Mail::send('emails.order',
                [
                    'quick_buy' => 1,
                    'order' => $order,
                ], function ($message) use ($request) {
                    $email = \App\Models\Setting::getVar('email_support');
                    $caption = 'Быстрый заказ';
                    $message->to($email)->subject($caption);
                });
            $phone = \App\Models\Setting::getVar('phone_number')['free'];
            $siteUrl = env('DEV_SITE_URL', $request->root());
            Mail::send('emails.order_for_user',
                [
                    'order' => $order,
                    'phone' => strip_tags($phone),
                    'siteUrl' => $siteUrl,
                ], function ($message) use ($request) {
                    $caption = 'Ваш заказ с сайта fit2u';
                    $message->to($request->input('email'))->subject($caption);
                });
        }
        $res['status'] = 200;
        $res['action'] = 'openModal';
        $res['modal'] = view('modals.order_success', ['user_name' => $data['name'], 'order_id' => $order->id])->render();

        return $res;
    }


    /**
     * Метод отложить товар
     * @param $id
     * @return array
     */
    public function defer($id) {
        if(session()->has('products.defer.'.$id)) {
            session()->forget('products.defer.'.$id);
        } else {
            session()->put('products.defer.'.$id, 1);
        }

        $defer = session()->get('products.defer');
        return [
            'count' => count($defer),
            'selector' => '.js-wishlist-quantity',
            'action' => 'updateCounter',
        ];
    }


    /**
     * @param Request $request
     * @return array
     */
    public function comments(Request $request) {
        $product_id = $request->input('product_id');
        $page = $request->input('page', 1);
        $perPage = $page == 1 ? 400 : 5; // if page = 1 then show all comments

        $comments = ProductComment::published();
        if($product_id) {
            $comments->where('product_id', $product_id);
        }
        $comments = $comments->paginate($perPage);
        $next_page = $comments->lastPage() > $comments->currentPage() ? ($comments->currentPage() + 1) : null; // номер следующей страницы
        $count = $next_page ? $comments->total() - ($comments->currentPage() * $comments->perPage()) : 0; // количество оставшихся комментариев


        $html = view('catalog.products.comments', compact('comments'))->render();

        return [
            'html' => $html,
            'action' => $page == 1 ? 'paginationReplace' : 'paginationAppend',
            'model' => 'comments',
            'total' => $comments->total(),
            'currentPage' => $comments->currentPage(),
            'next_page' => $next_page,
            'count' => $count,
        ];
    }

    /**
     * Добавление коментария к товару
     * @param Request $request
     * @param $product_id
     * @return \Illuminate\Http\JsonResponse|string
     * @internal param $id
     */
    public function comment(Request $request, $product_id) {

        $data = $request->input();

        //Статус - На модерации
        $data['status'] = 0;
        $data['message'] = $data['text'];
        $data['product_id'] = $product_id;

        $validatorOptions = [
            'name' => 'required',
            'text' => 'required',
        ];
        $validator = Validator::make($data, $validatorOptions);

        if ($validator->fails()) {
            return ['message' => 'При добавлении комментария произошла ошибка. Попробуйте снова.'];
        }


        $data['rating'] = floatval($request->rating);

        ProductComment::create($data);

        $html = view('catalog.products.comment_success', ['name' => $data['name']])->render();

        return [
            'html' => $html,
            'action' => 'commentSuccess',
        ];
    }

    /**
     * Положить товар в корзину
     * @param $id
     * @param int $cnt
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request, $id, $cnt = 1) {
        $response = [];

        $is_fast = $request->input('is_fast', 0);
        $cnt = $cnt ?: 1;

        $size = $request->input('size', 0);
        if($is_fast)
        {
            // Open fast order form modal
            $product = Product::find($id);
            $response = [
                'action' => 'openModal',
                'modal' => view('modals.quick_order_product', compact('product', 'cnt', 'size'))->render(),
            ];
        }
        else
        {
            // Put different sizes separately, products without size attribute has $size = 0

            // Increase product count if size already in cart
            if(session()->has('products.cart.'.$id.'.'.$size))
            {
                session()->put('products.cart.'.$id.'.'.$size.'.cnt', session()->get('products.cart.'.$id.'.'.$size.'.cnt') + $cnt);
            }
            else
            {
                $product = Product::published()->find($id);
                if(!$product) {
                    Log::warning('product not found -'.$id);
                }
                session()->put('products.cart.'.$id.'.'.$size, [
                    'cnt' => $cnt,
                    'price' => $product->price
                ]);

                // Модальное окно "Товар добавлен"
                $response['modal'] = view('modals.cart_add', compact('product','cnt','size'))->render();
            }

            // Добавляем доп. параметры, которые разделяют товар на варианты.
            // Например, размер, цвета, рисунок, ёмкость накопителя и т. д.
            $extra_params = $request->all();
            if(!empty($extra_params))
                foreach($extra_params as $param => $value) {
                    session()->put('products.cart.'.$id.'.'.$size.'.extra.'.$param, $value);
                }

            $cart = session()->get('products.cart');
            $response['amount'] = 0;

            $count = 0;
            foreach($cart as $product_id => $items)
            {
                foreach( $items as $product )
                {
                    $response['amount'] += $product['price']*$product['cnt'];
                    $count++;
                }
            }


            $response['id'] = $id;
            $response['action'] = 'updateCart';
            $response['count'] = $count;
            $response['unit_count'] = $cart[$id][$size]['cnt'];
            $response['count_name'] = Lang::choice('товар|товара|товаров', count($cart), [], 'ru');
        }
        return $response;
    }

    /**
     * Убрать товар из корзины
     * @param $id - id товара
     * @param $size - размер товара
     * @return
     */
    public function removeFromCart($id, $size) {
        $response = [];
        session()->forget('products.cart.'.$id.'.'.$size);
        $cart = session()->get('products.cart');
        $response['amount'] = 0;
        $count = 0;

        foreach($cart as $product_id => $items)
        {
            // Remove product completely if all sizes removed
            if( !count($items) )
                session()->forget('products.cart.'.$id);

            foreach( $items as $p_size => $product )
            {
                $response['amount'] += $product['price']*$product['cnt'];
                $count++;
            }
        }

        if(count($cart) == 0)
            session()->forget('products.cart');

        $response['action'] = 'updateCart';
        $response['count'] = $count;
        $response['removed'] = $id.'-'.$size;
//        $response['count_name'] = Lang::choice('товар|товара|товаров', count($cart), [], 'ru');

        return response()->json($response);
    }

    /**
     * Update cart product quantity
     * @param Request $request
     * @return array
     */
    public function updateCartQuantity(Request $request)
    {
        $id = $request->input('id');
        $quantity = $request->input('quantity', 1);
        $size = $request->input('size', 0);

        if($id && session()->has( 'products.cart.'. $id . '.' . $size ))
        {
            session()->put('products.cart.'. $id . '.' . $size .'.cnt', $quantity);

            $count = 0;
            foreach($cart = session()->get('products.cart') as $items ){
                $count += count($items);
            }
            $res = [
                'count' => $count,
                'action' => 'updateCart',
            ];
        }
        else
        {
            $res = [
                'error' => 1,
                'message' => 'Error, something went wrong!',
            ];
        }

        return $res;
    }

    /**
     * Сохранение изменений в корзине
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cartEdit(Request $request) {
        if(!$request->has('products') || !is_array($request->input('products')) || !count($request->input('products')) || !session()->has('products.cart'))  {
            return redirect()->route('cart');
        }
        $cart = session()->get('products.cart');
        session()->forget('products.cart');
        foreach($request->input('products') as $id => $items) {
            if(!isset($cart[$id])) {
                continue;
            }
            foreach($items as $size => $cnt)
            {
                session()->put('products.cart.'.$id.'.'.$size, [
                    'cnt' => $cnt,
                    'price' => $cart[$id][$size]['price'],
                    'extra' => isset($cart[$id][$size]['extra']) ? $cart[$id][$size]['extra'] : '',
                ]);
            }
        }

        if($request->input('is_fast', 0))
        {
            // Open fast order form modal
            $response = [
                'action' => 'openModal',
                'modal' => view('modals.quick_order_cart')->render(),
            ];
            return $response;
        }

        return redirect()->route('order');
    }
    /*
    * return modal view
     */
    public function modal(Request $request) {
        $modal = $request->input('modal');
        $response = [
            'action' => 'openModal',
            'modal' => view('modals.'.$modal)->render(),
        ];
        return $response;
    }

    /**
     * News pagination
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function news(Request $request) {
        $perPage = $request->page == 1 ? 400 : 12; // page = 1 means show all items on one page
        $news = News::published()->recent()->paginate($perPage);

        $next_page = $news->lastPage() > $news->currentPage() ? ($news->currentPage() + 1) : null; // номер следующей страницы
        $count = $next_page ? $news->total() - ($news->currentPage() * $news->perPage()) : 0; // количество оставшихся новостей

        return [
            'html' => view('news.list', compact('news'))->render(),
            'action' => $request->page == 1 ? 'paginationReplace' : 'paginationAppend',
            'model' => 'news',
            'total' => $news->total(),
            'currentPage' => $news->currentPage(),
            'nextPage' => $next_page,
            'count' => $count,
        ];
    }
    /**
     * News pagination
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function articles(Request $request) {
        $perPage = $request->page == 1 ? 400 : 12; // page = 1 means show all items on one page
        $articles = Article::published()->recent()->paginate($perPage);

        $next_page = $articles->lastPage() > $articles->currentPage() ? ($articles->currentPage() + 1) : null; // номер следующей страницы
        $count = $next_page ? $articles->total() - ($articles->currentPage() * $articles->perPage()) : 0; // количество оставшихся новостей

        return [
            'html' => view('articles.list', compact('articles'))->render(),
            'action' => $request->page == 1 ? 'paginationReplace' : 'paginationAppend',
            'model' => 'article',
            'total' => $articles->total(),
            'currentPage' => $articles->currentPage(),
            'nextPage' => $next_page,
            'count' => $count,
        ];
    }

    /**
     * Положить множество товаров в корзину
     */
    public function addToCartMultiple(Request $request) {

        $product_ids = $request->input('product_ids');
        $size_choose = $request->input('size_choose'); // Show modal with size choose input
        $sizes = $request->input('sizes');
        $checkout = $request->input('checkout'); // Proceed to cart
        $res = [];

        if( $product_ids )
        {
            $products = Product::with('attributes')
                ->whereIn('id', $product_ids)
                ->published()
                ->get();

            if( $products )
            {
                // Show size choose modal window
                if( $size_choose )
                {
                    $res = [
                        'action' => 'openModal',
                        'modal' => view('modals.cart_multiple_add', compact('products'))->render(),
                    ];
                }
                // If we already have sizes then add products to cart
                elseif($sizes)
                {
                    foreach( $products as $product ) {
                        $size = $sizes[$product->id];
                        // Increase product count if size already in cart
                        if (session()->has('products.cart.' . $product->id . '.' . $size))
                        {
                            session()->put('products.cart.' . $product->id . '.' . $size . '.cnt', session()->get('products.cart.' . $product->id . '.' . $size . '.cnt') + 1);
                        }
                        else
                        {
                            session()->put('products.cart.' . $product->id . '.' . $size, [
                                'cnt' => 1,
                                'price' => $product->price
                            ]);
                        }

                        // Добавляем доп. параметры, которые разделяют товар на варианты.
                        // Например, размер, цвета, рисунок, ёмкость накопителя и т. д.
                        $extra_params = $request->all();
                        if (!empty($extra_params))
                        {
                            foreach($extra_params as $param => $value)
                            {
                                session()->put('products.cart.'.$product->id.'.'.$size.'.extra.'.$param, $value);
                            }
                        }

                        $cart = session()->get('products.cart');
                        $res['amount'] = 0;

                        $count = 0;
                        foreach($cart as $product_id => $items)
                        {
                            foreach( $items as $size => $product )
                            {
                                $res['amount'] += $product['price']*$product['cnt'];
                                $count++;
                            }
                        }

                        if( $checkout )
                        {
                            $res = [
                                'action' => 'elementsRender',
                                'redirect' => route('cart'),
                            ];
                        }
                        else
                        {
                            $res['action'] = 'updateCart';
                            $res['count'] = $count;
                            $res['count_name'] = Lang::choice('товар|товара|товаров', count($cart), [], 'ru');
                        }


                    }
                }
                else
                {
                    $res = [
                        'error' => 1,
                        'message' => 'No sizes.',
                    ];
                }

            }
            else
            {
                $res = [
                    'error' => 1,
                    'message' => 'No products found by given ids.',
                ];
            }
        }
        else
        {
            $res = [
                'error' => 1,
                'message' => 'No products ids.',
            ];
        }

        return $res;






        $response = [];

        $is_fast = $request->input('is_fast', 0);
        $cnt = $cnt ?: 1;

        $size = $request->input('size', 0);
        if($is_fast)
        {
            // Open fast order form modal
            $product = Product::find($id);
            $response = [
                'action' => 'openModal',
                'modal' => view('modals.quick_order_product', compact('product', 'cnt', 'size'))->render(),
            ];
        }
        else
        {
            // Put different sizes separately, products without size attribute has $size = 0

            // Increase product count if size already in cart
            if(session()->has('products.cart.'.$id.'.'.$size))
            {
                session()->put('products.cart.'.$id.'.'.$size.'.cnt', session()->get('products.cart.'.$id.'.'.$size.'.cnt') + $cnt);
            }
            else
            {
                $product = Product::published()->find($id);
                session()->put('products.cart.'.$id.'.'.$size, [
                    'cnt' => $cnt,
                    'price' => $product->price
                ]);

                // Модальное окно "Товар добавлен"
                $response['modal'] = view('modals.cart_add', compact('product','cnt','size'))->render();
            }

            // Добавляем доп. параметры, которые разделяют товар на варианты.
            // Например, размер, цвета, рисунок, ёмкость накопителя и т. д.
            $extra_params = $request->all();
            if(!empty($extra_params))
                foreach($extra_params as $param => $value) {
                    session()->put('products.cart.'.$id.'.'.$size.'.extra.'.$param, $value);
                }

            $cart = session()->get('products.cart');
            $response['amount'] = 0;

            $count = 0;
            foreach($cart as $product_id => $items)
            {
                foreach( $items as $size => $product )
                {
                    $response['amount'] += $product['price']*$product['cnt'];
                    $count++;
                }
            }


            $response['id'] = $id;
            $response['action'] = 'updateCart';
            $response['count'] = $count;
            $response['unit_count'] = $cart[$id][$size]['cnt'];
            $response['count_name'] = Lang::choice('товар|товара|товаров', count($cart), [], 'ru');
        }
        return $response;
    }

}
