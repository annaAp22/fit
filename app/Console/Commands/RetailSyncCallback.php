<?php

namespace App\Console\Commands;

use App\Models\Callback;
use App\Models\Cooperation;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
/**
 * Отправляет заявку в retailCRM
 * Поддерживаемые заявки:callback, cooperation
 */
class RetailSyncCallback extends Command
{
    /**
     * @var string
     */
    protected $signature = 'retailcrm:sync_callback {method=callback} {id?} {--log}';

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
    public function sendOrder($order, $data)
    {
        //достаем url и ключ для подключения к crm
        $retail_api_key = env('RETAIL_CRM_API_KEY');
        $url = Setting::where('var', 'retailcrm_url')->first()->value;
        //достаем торговое предложение, нам нужен внешний id, по которому будем искать товар
        $retailOrderData = [
            'externalId' => $data['order_prefix'].'-' . $order->id,
            'firstName' => $order->name,
            'phone' => $order->phone,
            'email' => $order->email,
            'createdAt' => $order->created_at->format('Y-m-d H:i:s'),
            'orderMethod' => $data['method'],
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
            if($data['method'] == 'callback') {
                $order->update([
                    'send' => 1,
                ]);
            }
            return $response->id;
        } else {
            if (isset($response['errors'])) {
                $err = 'RetailCRM ' . json_encode($response['errors']);
                Log::error($err);
                $this->info($err);
            } else {
                $err = $response->getErrorMsg();
                if ($err == 'Order already exists.') {
                    if($data['method'] == 'callback') {
                        $order->update([
                            'send' => 1,
                        ]);
                    }
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
        $method = $this->argument('method');
        $id = $this->argument('id');
        $data = [
            'method' => $method
        ];
        if($method == 'callback') {
            if($id) {
                $order = Callback::find($id);
            } else {
                $order = Callback::last()->first();
            }
            $data['order_prefix'] = 'callback';
        }elseif($method == 'cooperation') {
            if($id) {
                $order = Cooperation::find($id);
            } else {
                $order = Cooperation::notNotified()->first();
            }
            $data['order_prefix'] = 'cp';
        } else {
            $this->info('unknown method');
            return false;
        }

        if (!$order) {
            $this->info('orders not found');
            return false;
        }
        $order_id = $this->sendOrder($order, $data);
        if ($order_id) {
            $this->info(' success, was created RetailCRM order:' . $order_id);
        }
    }
}
