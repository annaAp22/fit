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

    protected function create(Request $request)
    {
        $result = array(
            'action' => 'openModal',
        );

        $data = $request->input();
        $data['phone'] = preg_replace('/[^+0-9]/', '', $data['phone']);
        $messages = null;
        $validator = $this->validator($data);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $messages = $messages->all();
        }else {
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
            return $result;
        }elseif(!$messages) {
            $messages = array(
                'other' => 'Непредвиденная ошибка'
            );
        }
        $result['modal'] = view('modals.registration_error', compact('messages'))->render();
        return $result;
    }
}
