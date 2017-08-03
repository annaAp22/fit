<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DeliveryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'sysname' => 'alpha_dash',
//            'email' => 'email',
//            'address' => 'required',
            'delivery_id' => 'required|integer',
//            'payment_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Необходимо указать Вашу фамилию, имя, отчество',
            'phone.required'  => 'Необходимо указать Ваш телефон',
            'email.email'  => 'E-mail заполнен не корректно',
            'address.required'  => 'Необходимо указать Ваш адрес',
            'address.delivery_id'  => 'Необходимо выбрать Способ доставки',
            'address.payment_id'  => 'Необходимо выбрать Способ оплаты',
        ];
    }


}
