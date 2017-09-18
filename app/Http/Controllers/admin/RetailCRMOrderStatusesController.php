<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\admin\RetailOrderStatusesRequest;
use App\Models\OrderStatuses;
use App\Models\RetailOrderStatuses;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class RetailCRMOrderStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $statuses = RetailOrderStatuses::get();
        $orderStatuses = OrderStatuses::get()->keyBy('id');
        foreach ($statuses as $status) {
            $status->status_name = $orderStatuses[$status->status_id]->name;
        }
        return view('admin.retail_order_statuses.index', compact('statuses'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $statuses = OrderStatuses::get();
        return view('admin.retail_order_statuses.create', compact('statuses'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RetailOrderStatusesRequest $request)
    {
        //
        $data = $request->input();
        RetailOrderStatuses::create($data);
        return redirect()->route('admin.retailcrm_order_statuses.index')->withMessage('Статус добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $status = RetailOrderStatuses::find($id);
        $orderStatuses = OrderStatuses::all();
        return view('admin.retail_order_statuses.edit', compact('status', 'orderStatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->input();
        $order = RetailOrderStatuses::find($id);
        $order->update($data);
        return redirect()->route('admin.retailcrm_order_statuses.index')->withMessage('Статус добавлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = RetailOrderStatuses::find($id);
        RetailOrderStatuses::destroy($id);
        return redirect()->route('admin.retailcrm_order_statuses.index')->withMessage('Статус '.$order->name.' удален');
    }
    public function load() {
        $retail_api_key = env('RETAIL_CRM_API_KEY');
        $url = Setting::where('var', 'retailcrm_url')->first()->value;
        $client = new \RetailCrm\ApiClient(
            $url,
            $retail_api_key
        );
        //достаем статусы заказов из retailCRM
        try {
            $response = $client->request->statusesList();
        } catch (\RetailCrm\Exception\CurlException $e) {
            $err = 'RetailCRM '.$e->getMessage();
            Log::error($err);
            return redirect()->route('admin.retailcrm_order_statuses.index')->withMessage($err);
        }

        if($response->isSuccessful()) {
            $statuses = $response->statuses;
            $defaultStatus = OrderStatuses::where('name', 'wait')->first();
            foreach ($statuses as $status) {
                if(!RetailOrderStatuses::where('sysname',$status['code'])->first()) {
                    RetailOrderStatuses::create([
                        'sysname' => $status['code'],
                        'name' => $status['name'],
                        'status_id' => $defaultStatus?$defaultStatus->id:1,
                    ]);
                }
            }
        } else {
            return redirect()->route('admin.retailcrm_order_statuses.index')->withMessage('Не удалось загрузить статусы из crm');
        }
        return redirect()->route('admin.retailcrm_order_statuses.index')->withMessage('Статусы загружены, требуется выставить соответствия со статусами сайта');
    }
}
