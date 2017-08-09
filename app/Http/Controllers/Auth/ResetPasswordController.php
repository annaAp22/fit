<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, $this->rules());
    }
    public function reset(Request $request)
    {
        $validator = $this->validator($request->input());
        if ($validator->fails()) {
            $messages = $validator->messages();
            $messages = $messages->all();
            $title = 'Изменение пароля';
            $result = array(
                'action' => 'openModal',
                'modal' => view('modals.validation_error', compact('messages', 'title'))->render(),
            );
            return $result;
        } else {
        }
        $response = $this->broker()->reset($this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        return array(
            'status' => $response
        );
    }
}
