<?php

namespace App\Mail;

use App\Models\Callback;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CallbackMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Callback $callback)
    {
        //
        $this->callback = $callback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $caption = 'Запрос обратного звонка '.env('APP_URL');
        if(!isset($this->callback)) {
            $this->callback = Callback::last()->first();
        }
        $email = Setting::getVar('email_support');
        return $this->to($email)->subject($caption)->view('emails.support.callback')
            ->with([
                'callback' => $this->callback,
            ]);
    }
}
