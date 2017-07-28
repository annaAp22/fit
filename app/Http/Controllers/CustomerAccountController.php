<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

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
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|min:12|unique:users',
        ]);
    }

    protected function logout()
    {
        Auth::logout();
        $result = array(
            'action' => 'elementsRender',
            'text' => [
                '.js-user-name' => 'Войти / Вступить'
            ],
            'hide' => [
                '#js-autorized',
            ],
            'show' => [
                '#js-not-autorized',
            ],
            'redirect' => '/',
        );
        return json_encode($result);
    }
    protected function login(Request $request)
    {
        $data = $request->input();
        $user = User::where('email', $data['email'])->published()->first();
        if ($user && $auth = Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember_token'])) {
            // Пользователь запомнен...
            $result = array(
                'action' => 'elementsRender',
                'text' => [
                    '.js-user-name' => $user->name
                ],
                'hide' => [
                    '#js-not-autorized',
                ],
                'show' => [
                    '#js-autorized',
                ],
                'reload' => true,
            );
            return json_encode($result);
        } else {
            $result = array(
                'action' => 'openModal',
            );
            $result['modal'] = view('modals.login_error')->render();
            return $result;
        }
    }
    protected function create(Request $request)
    {
        $result = array(
            'action' => 'openModal',
        );

        $data = $request->input();
        $messages = null;
        $data['phone'] = preg_replace('/[^+0-9]/', '', $data['phone']);
        $validator = $this->validator($data);
        //проверяем поля, предназначенные для защиты от бота
        $validator->after(function($validator) use($data)
        {
            if(!isset($data['last_name']) or $data['last_name'] != 666) {
                $validator->errors()->add('other', 'Регистрация не доступна');
            }
        });
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
        } else {
            $shopper = UserGroup::where('name', 'customer')->first();
            if(!$shopper) {
                $messages = array(
                    'other' => 'На данный момент регистрация отключена'
                );
            }else {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone']?:null,
                    'password' => bcrypt($data['password']),
                    'group_id' => $shopper->id,
                    'status' => 1,
                ]);
            }
        }
        if(isset($user)) {
            $result['modal'] = view('modals.registration_success')->render();
            Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
            return $result;
        }elseif(!$messages) {
            $messages = array(
                'other' => 'Непредвиденная ошибка'
            );
        }
        $result['modal'] = view('modals.registration_error', compact('messages'))->render();
        return $result;
    }
    public function update(Request $request) {
        $data = $request->input();
        $data['phone'] = preg_replace('/[^+0-9]/', '', $data['phone']);
        $validate_arr = array(
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|min:12',
            'year' => 'numeric',
            'day' => 'numeric',
            'month' => 'numeric',
        );
        $validator = Validator::make($data, $validate_arr);
        $title = 'Изменение данных';
        $result = array(
            'action' => 'openModal',
        );
        $user = Auth::user();
        //не даем создать почту, которая есть у другого пользователя
        $validator->after(function($validator) use ($user, $data)
        {
            $other_user = User::where('id', '<>', $user->id)->where('email', $data['email'])->published()->first();
            if($other_user) {
                $validator->errors()->add('duplicate_email', 'Этот email уже используется другим пользователем');
            }
        });
        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            $result['modal'] = view('modals.validation_error', compact('messages', 'title'))->render();
        }else {
            if(!isset($data['year']) || !isset($data['month']) || !isset($data['day'])) {
                $data['birthday'] = null;
            } else {
                $data['birthday'] = strtotime($data['day'].'.'.$data['month'].'.'.$data['year']);
            }
//123456
            $user->update([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'birthday' => $data['birthday'],
                'subscription' => isset($data['subscription']) ? 1 : 0,
            ]);

            $result['modal'] = view('modals.change_user_success', compact('user'))->render();
        }
        return $result;
    }
}
