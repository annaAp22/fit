<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use Slug;
use Auth;

class CategoryRequest extends Request
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
        return [
            'name' => 'required',
            'sysname' => 'required|sysname|unique:categories,sysname,'.$this->route('category'),
            'icon' => 'image',
            'img' => 'image',
        ];
    }

    protected function getValidatorInstance() {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('parent_id', 'exists:categories,id', function($input) {
            return !is_null($input->parent_id);
        });

        return $validator;
    }
}
