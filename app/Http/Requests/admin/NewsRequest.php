<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use Slug;

class NewsRequest extends Request
{
    public function validate() {
        if(empty($this->request->get('sysname')))
            $this->request->set('sysname', Slug::make($this->request->get('name'), '-'));
        parent::validate();
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
            'sysname' => 'required|sysname|unique:news,sysname,'.$this->route('news'),
            'body' => 'required'
        ];
    }
}
