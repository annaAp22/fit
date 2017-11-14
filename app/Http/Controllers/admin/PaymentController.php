<?php

namespace App\Http\Controllers\admin;

use App\Models\Delivery;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', new \App\Models\Order());
        $payments = Payment::orderBy('id', 'desc')->withTrashed()->paginate(10);

        return view('admin.payments.index', ['payments' => $payments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deliveries = Delivery::published()->get();
        return view('admin.payments.create', compact('deliveries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\admin\PaymentRequest $request)
    {
        Payment::create($request->all());
        return redirect()->route('admin.payments.index')->withMessage('Способ оплаты добавлен');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $deliveries = Delivery::published()->get();
        return view('admin.payments.edit', compact('payment', 'deliveries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\admin\PaymentRequest $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->deliveries()->sync($request->input('deliveries'));
        $payment->update($request->except('deliveries'));

        return redirect()->route('admin.payments.index')->withMessage('Способ оплаты изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Payment::destroy($id);
        return redirect()->route('admin.payments.index')->withMessage('Способ оплаты удален');
    }


    /**
     * Востановление мягко удаленной категории
     * @param $id
     * @return mixed
     */
    public function restore($id) {
        Payment::withTrashed()->find($id)->restore();
        return redirect()->route('admin.payments.index')->withMessage('Способ оплаты востановлен');
    }

}
