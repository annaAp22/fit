<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;

class SettingsRequest extends Request
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
            'type'=> 'required|in:string,array',
            'var' => 'required|sysname|unique:settings,var,'.$this->route('setting'),
            'value'=>'required_if:type,string',
            'values'=>'required_if:type,array',
        ];
    }
}
