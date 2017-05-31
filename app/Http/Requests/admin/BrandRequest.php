<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use Slug;

class BrandRequest extends Request
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
            'sysname' => 'required|sysname|unique:brands,sysname,'.$this->route('brand'),
            'img' => 'image'
        ];
    }
}
