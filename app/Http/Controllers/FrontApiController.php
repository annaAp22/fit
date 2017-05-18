<?php

namespace App\Http\Controllers;

use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\Order;

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
            return response()->json(['message' => 'При оформлении подписки произошла ошибка. Попробуйте снова.']);

        $subscribe = \App\Models\Subscriber::firstOrCreate([
            'email' => $request->input('email'),
            'act' => $request->has('act') && $request->input('act') ? 
                $request->input('act') : 1
        ]);

        return response()->json(['result' => 'ok']);
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

        return response()->json(['result' => 'ok']);
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
             'phone' => $request->input('phone')], function ($message) use ($request){
                $message->to(\App\Models\Setting::getVar('email_support'))->subject('Запрос обратного звонка '.$request->root());
            });

        return response()->json(['result' => 'ok']);
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
        //фильтр по бренду
        if($request->has('brand_id') && $request->input('brand_id')) {
            $products->where('brand_id', intval($request->input('brand_id')));
        }
        //фильтр по акции
        if($request->has('act') && $request->input('act')) {
            $products->where('act', 1);
        }
        //фильтр по Новинкам
        if($request->has('new') && $request->input('new')) {
            $products->where('new', 1);
        }
        //фильтр по Хитам
        if($request->has('hit') && $request->input('hit')) {
            $products->where('hit', 1);
        }
        //фильтр по ценам от - до
        if($request->has('price_from') && $request->input('price_from')) {
            $products->where('price', '>=', intval($request->input('price_from')));
        }
        if($request->has('price_to') && $request->input('price_to')) {
            $products->where('price', '<=', intval($request->input('price_to')));
        }
        //фильтры по атрибутам
        if ($request->has('attribute')) {
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
            if($request->has('tag_id'))
                $products->orderBy('product_tag.sort');
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

//        $response['products'] = $products; // TODO: REALLY NEED THIS????
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

        if ($validator->fails())
            return response()->json(['error' => 1, 'message' => 'При оформлении заказа произошла ошибка. Попробуйте снова.']);

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'datetime' => date('Y-m-d H:i:s'),
            'status' => 'wait',
            'email' => $request->input('email', 'no email'),
        ];

        if($is_multiple)
        {
            $order = Order::create($data);
            $amount = 0;
            foreach(session()->get('products.cart') as $product_id => $product) {
                $order->products()->attach($product_id, [
                    'cnt'          => $product['cnt'],
                    'price'        => $product['price'],
                    'extra_params' => isset($product['extra']) ? json_encode($product['extra']) : '',
                ]);
                $amount += $product['cnt']*$product['price'];
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
        }

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
        return ['count' => count($defer)];
    }
    public function comments(Request $request) {
        $product_id = $request->input('product_id');
        $perPage = $request->input('per_page', 1) == 'all' ? 1000 : 5;
        $comments = ProductComment::published();
        if($product_id) {
            $comments->whereHas('product', function($query) use($product_id) {
                $query->where('id', $product_id);
            });
        }
        $comments = $comments->paginate($perPage);
        $next_page = $comments->lastPage() > $comments->currentPage() ? ($comments->currentPage() + 1) : null; // номер следующей страницы
        $count = $next_page ? $comments->total() - ($comments->currentPage() * $comments->perPage()) : 0; // количество оставшихся комментариев
        $response = '';
        foreach ($comments as $comment)
            $response.= view('reviews.review', ['review' => $comment])->render();
        return [
            'clear' => $request->input('per_page', 1) == 'all', // если true заменяем комментарии на странице, false добавляем в конецclear' => $page == 'all', // если true заменяем комментарии на странице, false добавляем в конец
            'html' => $response,
            'action' => 'appendComments',
            'total' => $comments->total(),
            'currentPage' => $comments->currentPage(),
            'next_page' => $next_page,
            'count' => $count,
            // ... другие необходимые параметры пагинации можно посмотреть в документация к методу paginate()
        ];
    }
    /***/
    public function getComments(Request $request) {
        $data = $request->input();
        $product = Product::find($data['product_id']);
        if(!$product)
            return null;
        if(isset($data['count'])) {
            $reviews = $product->getComments($data['count']);
        }else
            $reviews = $product->comments;
        $response = '';
        foreach ($reviews as $review)
            $response.= view('reviews.review', ['review' => $review])->render();
        echo $response;
    }
    /**
     * Добавление коментария к товару
     * @param $id
     * @param Request $request
     * @return string|\Illuminate\Http\JsonResponse
     */
    public function comment(Request $request) {
        $data = $request->input();
        //Статус - На модерации
        $data['status'] = 0;
        $data['message'] = $data['text'];
        $validatorOptions = $data['product_id'] == 0 ?
            [
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required',
            ] : [
                'product_id' => 'required|exists:products,id',
                'name' => 'required',
                'text' => 'required',
            ];
        $validator = Validator::make($data, $validatorOptions);

        if ($validator->fails()) {
            return response()->json(['message' => 'При добавлении комментария произошла ошибка. Попробуйте снова.']);
        }

        if($data['product_id'] == 0)
            \App\Models\Review::create($data);
        else {
            $data['rating'] = floatval($request->rating);
            \App\Models\Review::create($data);
            \App\Models\ProductComment::create($data);
        }

        return response()->json(['result' => 'ok']);
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

}
