<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Order;

class OrderRequest extends Request
{
    private $rules = [
        'datetime' => 'required|date',
        'name' => 'required',
        'phone' => 'required',
        'email' => 'email',
        'address' => 'required',
        'delivery_id' => 'required',
        'payment_id' => 'required',
        'status' => 'required'
    ];

    public function validate() {
        $this->prepareForValidation();
        parent::validate();
    }


    protected function prepareForValidation() {
        $this->rules['status'] = 'required|in:'.implode(',', array_keys(Order::$statuses));
    }

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
        return $this->rules;
    }
}
