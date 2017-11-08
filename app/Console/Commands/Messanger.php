<?php

namespace App\Console\Commands;

use App\Models\Cooperation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Messanger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:message {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email from table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    private function sendCooperation($data) {
        $order = Cooperation::notNotified()->first();
        if($order) {
            //Делаем пометку, что письмо отправлено
            $order->update([
                'notify' => 1,
            ]);
            //Отправляем сообщение о полученной заявке на сотрудничество
            Mail::send('emails.cooperation2', compact('order')
                , function ($message) use($data) {
                    $caption = 'Запрос на сотрудничество';
                    $message->to($data['email'])->subject($caption);
            });
            //Делаем пометку, что отправка прошла успешно
            $order->update([
               'success' => 1,
            ]);
            $this->info('order N-'.$order->id.' send to '.$data['email']);
            //отправка заявки в системы интеграции
            $this->call('retailcrm:sync_callback', [
                'method' => 'cooperation',
                'id' => $order->id,
            ]);
            //
        } else {
            $this->info('orders not found');
        }

    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $type = $this->argument('type');
        $data = [
            'email' => env('MAIL_FAKE', \App\Models\Setting::getVar('email_support')),
        ];
        if($type == 'cooperation') {
            $this->sendCooperation($data);
        }
    }
}
