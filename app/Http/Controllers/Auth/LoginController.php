<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function redirectTo() {
        $group_name = Auth::user()->group->name;
        if(array_search($group_name, ['admin', 'moderator', 'manager', 'content-manager']) !== false)
            return route('admin.main');
        elseif($group_name == 'exchange')
        {
            return route('exchange');
        }
        else
            return route('customer.dashboard');
    }

    /**
     * Overrides Illuminate\Foundation\Auth\AuthenticatesUsers trait to check user status
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            array_merge($this->credentials($request), ['status' => 1]), $request->has('remember')
        );
    }
}
