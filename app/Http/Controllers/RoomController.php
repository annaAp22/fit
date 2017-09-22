<?php
/*
 * Customer personal cabinet page controller.
 */

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Referral;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
  private $perpage = 10;
  private $pageLimit = 200;
  //
  public function index() {
    $user = Auth::user();
    if(!$user) {
      abort('404');
    }
    $this->setMetaTags();
    $orders_count = $orders = Order::where('email', $user->email)->orWhere('customer_id', $user->id)->count();
    $orders_count = min($orders_count, $this->pageLimit);
    return view('content.room', compact('user', 'orders_count'));
  }
  public function orders(Request $request, $is_referrals = false) {
    $user = Auth::user();
    if(!$user) {
      abort('404');
    }
    $this->setMetaTags();
    //получаем коллекцию заказов в зависимости от номера страницы и количества на странице
    //если указан флаг рефералов, то получаем заказы рефералов, а не самого пользователя
    if($is_referrals && isset($user->partner)) {
        $referrals = $user->partner->referrals;
        $phones = $referrals->pluck('phone');
        $orderRequest = Order::whereIn('phone', $phones)->where('extra_params', 'like', '%referrer%')->orderBy('created_at', 'decs');
    } else {
        $is_referrals = false;
        $orderRequest = Order::where('email', $user->email)->orWhere('customer_id', $user->id)->orderBy('created_at', 'decs');
    }
    $page = $request->input('page', null);
    $lastOrderCount = 0;
    if($page == null) {
      $orders = $orderRequest->take($this->pageLimit)->paginate($this->perpage);
    }
    else {
      $lastOrderCount = ($page - 1) * $this->perpage;
      if($request->input('perPage') == 'all') {
        $orders = $orderRequest->skip($lastOrderCount)->take($this->pageLimit)->get();
      } else {
        $orders = $orderRequest->take($this->pageLimit)->paginate($this->perpage);
      }
    }
    //получаем коллекцию скидок
    $deliveries = Delivery::select('id', 'price')->published()->get()->keyBy('id');
    //дописываем в каждый товар заказа его размер, а в сам заказ стоимость скидки
    foreach ($orders as $order) {
      $order->products = $order->getProducts();
      $discount_kf = (100 - $order->discount_percent) / 100;
      foreach($order->products as $product) {
          $product->discount_price = $product->pivot->price * $discount_kf;
          if(isset($product->pivot->extra_params)) {
          $extra_params = json_decode($product->pivot->extra_params);
          if(isset($extra_params->size) ){
            $product->size = $extra_params->size;
          }else {
            $product->size = 0;
          }
        }else {
          $product->size = 0;
        }
      }
      $params = $order->extra_params;
      if(isset($params['delivery_price'])) {
        $order->delivery_price = $params['delivery_price'];
        continue;
      }
      $id = $order->delivery_id;
      if(isset($id)) {
        $order->delivery_price = isset($deliveries[$id]) ? $deliveries[$id]->price : 0;
      }else {
        $order->delivery_price = 0;
      }
    }
    
    $orders_count = count($orders);
    //считаем, сколько страниц осталось
    if(!method_exists($orders, 'currentPage') || $orders->currentPage() == $orders->lastPage()) {
      $ordersRemained = 0;
    }else {
      $ordersRemained = $orders->total() - $orders->currentPage() * $orders->perPage();
    }
    //нам нужно знать нечетность каждого приходящего заказа среди всех полученных заказов
    $odd = intval($request->input('odd', 1));
    //если ajax запрос, то отправляем объект с информацией, что, куда добавить, что изменить. Если нет - получаем страницу заказов
    if($request->isXmlHttpRequest()) {
      if($orders_count == 0) {
        return [];
      }
      //получаем представления заказов
        if($is_referrals) {
            $tableRows = view('blocks.referrals_orders_rows', compact('orders', 'odd', 'lastOrderCount'))->render();
        } else {
            $tableRows = view('blocks.orders_rows', compact('orders', 'odd', 'lastOrderCount'))->render();
        }
      $odd = $odd + $orders_count % 2;
      //получает представление постраничной навигации
      if($ordersRemained == 0) {
        $pageNavigation = '';
      } else {
        $pageNavigation = view('blocks.orders_pages', compact('odd', 'ordersRemained', 'page'))->render();
      }
      $result = array(
          'action' => 'elementsRender',
          'text' => [
            '.js-page-navigation' => $pageNavigation,
          ],
          'append' => [
            '.js-orders-table' => $tableRows
          ],
      );
      return $result;
    } else {
        if($is_referrals) {
            $active = 'referrals_orders_history';
            return view('content.referrals_orders_history', compact('orders', 'odd', 'ordersRemained', 'page', 'lastOrderCount', 'active'));
        }else {
            $active = 'orders_history';
            return view('content.orders_history', compact('orders', 'odd', 'ordersRemained', 'page', 'lastOrderCount', 'active'));
        }

    }

  }
  public function referralsOrders() {

  }
}
