<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Order;

use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new \App\Models\Order());
        $filters = $this->getFormFilter($request->input());

        $orders = Order::orderBy('id', 'desc');
        if (!empty($filters) && !empty($filters['name'])) {
            $orders->where('name', 'LIKE', '%'.$filters['name'].'%');
        }
        if (!empty($filters) && !empty($filters['date_from'])) {
            $orders->where('datetime', '>=', (new Carbon($filters['date_from']))->format('Y.m.d'));
        }
        if (!empty($filters) && !empty($filters['date_to'])) {
            $orders->where('datetime', '<=', (new Carbon($filters['date_to'].' 23:59'))->format('Y.m.d H:i'));
        }

        if (!empty($filters) && isset($filters['status']) && $filters['status']!='') {
            $orders->where('status', $filters['status']);
        }
        if (!empty($filters) && isset($filters['deleted']) && $filters['deleted']) {
            $orders->withTrashed();
        }
        $orders = $orders->paginate($filters['perpage'], null, 'page', !empty($filters['page']) ? $filters['page'] : null);

        return view('admin.orders.index', ['orders' => $orders, 'filters' => $filters]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with('products')->findOrFail($id);

        //способы доставки
        $deliveries = \App\Models\Delivery::orderBy('name')->get();
        //способы оплаты
        $payments = \App\Models\Payment::orderBy('name')->get();

        return view('admin.orders.edit', ['order' => $order, 'deliveries' => $deliveries, 'payments' => $payments]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\OrderRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->all();
        $data['datetime'] = (new Carbon($data['datetime']))->format('Y.m.d H:i');
        $order->update($data);

        return redirect()->route('admin.orders.index')->withMessage('Заказ изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::destroy($id);
        return redirect()->route('admin.orders.index')->withMessage('Заказ удален');
    }


    /**
     * Востановление мягко удаленной категории
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        Order::withTrashed()->find($id)->restore();
        return redirect()->route('admin.orders.index')->withMessage('Заказ востановлен');
    }
}
