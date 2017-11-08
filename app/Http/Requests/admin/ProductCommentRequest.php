<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class ProductCommentRequest extends Request
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
            'product_id' => 'required|exists:products,id',
            //разрешаем только буквы и пробел
            'name' => 'required|min:3|regex:/^[a-z,а-я,\x20].*$/i',
            'date' => 'required|date',
            //исключаем домены в тексте
            'text' => 'required|min:5|regex:/^(?!.*\.[a-z].)/i',
        ];
    }
}
