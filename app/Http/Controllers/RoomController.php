<?php
/*
 * Customer personal cabinet page controller.
 */

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
  private $perpage = 20;
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
  public function orders(Request $request) {
    $user = Auth::user();
    if(!$user) {
      abort('404');
    }
    $this->setMetaTags();
    //получаем коллекцию заказов в зависимости от номера страницы и количества на странице
    $orderRequest = Order::where('email', $user->email)->orWhere('customer_id', $user->id)->orderBy('created_at', 'decs');
    $perPage = Setting::getVar('perpage') ?: $this->perpage;
    $perPage = 10;
    $page = $request->input('page', null);
    $lastOrderCount = 0;
    if($page == null) {
      $orders = $orderRequest->take($this->pageLimit)->paginate($perPage);
    }
    else {
      $lastOrderCount = ($page - 1) * $perPage;
      if($request->input('perPage') == 'all') {
        $orders = $orderRequest->skip($lastOrderCount)->take($this->pageLimit)->get();
      } else {
        $orders = $orderRequest->take($this->pageLimit)->paginate($perPage);
      }
    }
    //получаем коллекцию скидок
    $deliveries = Delivery::select('id', 'price')->published()->get()->keyBy('id');
    //дописываем в каждый товар заказа его размер, а в сам заказ стоимость скидки
    foreach ($orders as $order) {
      $order->products = $order->getProducts();
      foreach($order->products as $product) {
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
    //считаем, сколько страниц осталосб
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
      $tableRows = view('blocks.orders_rows', compact('orders', 'odd', 'lastOrderCount'))->render();
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
      return view('content.orders_history', compact('orders', 'odd', 'ordersRemained', 'page', 'lastOrderCount'));
    }

  }
}
