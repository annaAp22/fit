<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderStatuses;
use App\Models\Partner;
use App\Models\PartnerTransfer;
use App\Models\RetailOrderStatuses;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OrdersUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:orders_update {--moySklad}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update orders statuses from retailCRM.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        if($this->option('moySklad')) {

        } else {
            //достаем url и ключ для подключения к crm
            $retail_api_key = env('RETAIL_CRM_API_KEY');
            $url = Setting::where('var', 'retailcrm_url')->first()->value;
            $completeStatuses = [
                'cancel', 'complete',
            ];
            $orders = Order::whereNotIn('status', $completeStatuses)->orderBy('id', 'desc')->take(100)->get();
            //Log::info($orders->pluck('id'));
            $orders = $orders->keyBy('id');
            $client = new \RetailCrm\ApiClient(
                $url,
                $retail_api_key
            );
            $filter = [
                'externalIds' => $orders->pluck('id')->toArray(),
            ];
            //достаем статусы заказов из retailCRM
            try {
                $response = $client->request->ordersList($filter, 1, 100);
            } catch (\RetailCrm\Exception\CurlException $e) {
                $err = 'RetailCRM '.$e->getMessage();
                Log::error($err);
                $this->info($err);
                return false;
            }
            if($response->isSuccessful()) {
                $retailStatuses = RetailOrderStatuses::all()->keyBy('sysname');
                foreach ($response->orders as $order) {
                    $id = $order['externalId'];
                    if(isset($orders[$id])) {
                        $temp_order = $orders[$id];
                        $retailStatus = $retailStatuses[$order['status']];
                        $temp_order->status = $retailStatus->status->sysname;
//                        начисляем процент от заказа партнеру и сохраняем в историю
                        if($temp_order->status == 'complete' && isset($temp_order->extra_params['referrer'])) {
                            $partner_id = $temp_order->extra_params['referrer'];
                            $partner = Partner::find($partner_id);
                            if($partner) {
                                if(isset($order['summ']) && isset($order['totalSumm']) && isset($order['delivery']['data']['price'])) {
                                    $discount = ($order['summ'] + $order['delivery']['data']['price'] - $order['totalSumm']);
                                } else {
                                    $discount = 0;
                                }
                                if($discount < 0) {
                                    $discount = 0;
                                }
                                $partner->accrue($discount);
                                PartnerTransfer::create([
                                    'partner_id' => $partner->id,
                                    'status' => 'accrue',
                                    'money' => $discount,
                                ]);
                            }
                        }
                        $temp_order->save();
                    }
                }
                //Log::info($response->orders);
            }else {
                Log::info($response->errors);
            }
        }
    }
}
