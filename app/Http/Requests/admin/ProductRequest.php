<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use Slug;
use Auth;

class ProductRequest extends Request
{

    public function validate() {
        $this->prepareForValidation();
        parent::validate();
    }


    protected function prepareForValidation() {
        if(empty($this->request->get('sysname'))) {
            $this->request->set('sysname', Slug::make($this->request->get('name'), '-'));
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('product');
        return [
            'categories' => 'required|array|not_empty|exists:categories,id',
            'name' => 'required',
            'descr' => 'max:200',
            'sysname' => 'required|sysname|unique:products,sysname,'.$id,
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'sku' => 'required|unique:products,sku,'.$id,
            'img' => 'image',
            'text' => 'required',
//            'brand_id' => 'exists:brands,id'
        ];
    }
}
