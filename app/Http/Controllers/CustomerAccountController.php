<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class CustomerAccountController extends Controller
{
    public function me() {
        $customer = Auth::user();

        return view('customer.dashboard', [
            'orders' => $customer->orders()->with(['delivery', 'payment'])->paginate(15),
        ]);
    }

    public function order($order_id) {
        $customer = Auth::user();
        $order = $customer->orders()
            ->with(['delivery', 'payment'])
            ->where('customer_id', $customer->id)
            ->where('id', $order_id)
            ->first();

        return view('customer.order', [ 'order' => $order, ]);
    }
}
