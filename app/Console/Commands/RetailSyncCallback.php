<?php

namespace App\Console\Commands;

use App\Models\Callback;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RetailSyncCallback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retailcrm:sync_callback {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * send order to retail crm
     * @return order id into crm or false
     * **/
    public function sendOrder($order)
    {
        //достаем url и ключ для подключения к crm
        $retail_api_key = env('RETAIL_CRM_API_KEY');
        $url = Setting::where('var', 'retailcrm_url')->first()->value;
        //достаем торговое предложение, нам нужен внешний id, по которому будем искать товар
        $retailOrderData = [
            'externalId' => 'callback-' . $order->id,
            'firstName' => $order->name,
            'phone' => $order->phone,
            'createdAt' => $order->created_at->format('Y-m-d H:i:s'),
            'orderMethod' => 'callback',
            'customFields' => [
                'roistat' => isset($order->extra['roistat']) ? $order->extra['roistat'] : null,
            ],
        ];
        $client = new \RetailCrm\ApiClient(
            $url,
            $retail_api_key
        );
        //если включено логирование, то не отправляем в лог вместо crm
        if ($this->option('log')) {
            Log::info($retailOrderData);
            $this->info('order logging');
            return false;
        }

        //создаем заказ в crm
        try {
            $response = $client->request->ordersCreate($retailOrderData);
        } catch (\RetailCrm\Exception\CurlException $e) {
            $err = 'RetailCRM ' . $e->getMessage();
            Log::error($err);
            $this->info($err);
            return false;
        }

        if ($response->isSuccessful() && 201 === $response->getStatusCode()) {
            $order->update([
                'send' => 1,
            ]);
            return $response->id;
        } else {
            if (isset($response['errors'])) {
                $err = 'RetailCRM ' . json_encode($response['errors']);
                Log::error($err);
                $this->info($err);
            } else {
                $err = $response->getErrorMsg();
                if ($err == 'Order already exists.') {
                    $order->update([
                        'send' => 1,
                    ]);
                    $err = 'RetailCRM ' . $err;
                    $this->info($err);
                    $this->info('Order is removed from the list, now');
                } else {
                    $err = 'RetailCRM ' . $err;
                    $this->info($err);
                }
                Log::error($err);
            }
            return false;
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $order = Callback::last()->first();
        if (!$order) {
            $this->info('callbacks not found');
            return false;
        }
        $this->sendOrder($order);
        $order_id = $this->sendOrder($order);
        if ($order_id) {
            $this->info(' success, was created RetailCRM order:' . $order_id);
        }
    }
}
