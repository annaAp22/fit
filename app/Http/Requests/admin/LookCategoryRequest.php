<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use Auth;

class LookCategoryRequest extends Request
{
    public function validate() {
        $this->prepareForValidation();
        parent::validate();
    }


    protected function prepareForValidation() {

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
        ];
    }

    protected function getValidatorInstance() {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('category_id', 'exists:look_categories,id', function($input) {
            return !is_null($input->category_id);
        });

        return $validator;
    }
}
