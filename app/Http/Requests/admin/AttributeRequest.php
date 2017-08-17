<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Attribute;
use Auth;

class AttributeRequest extends Request
{
    private $rules = [
        'name' => 'required',
        'sysname' => 'regex:/^[a-z_]+$/',
        'type' => 'required',

    ];

    public function validate() {
        $this->prepareForValidation();
        parent::validate();
    }


    protected function prepareForValidation() {
        $id = isset(Request::route()->parameters["attribute"]) ?
            Request::route()->parameters["attribute"] :
            null;

        $this->rules['name'] = 'required|unique:attributes,name,'.$id;

        $this->rules['type'] = 'required|in:'.implode(',', array_keys(Attribute::$types));

        if(Request::has('type') && Request::input('type')=='list')
            $this->rules['values'] = 'array|not_empty';

        if(Request::has('type') && Request::input('type') == 'integer')
            $this->rules['unit'] = 'required';
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
        return $this->rules;
    }
}
