<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Photo;

class PhotoRequest extends Request
{
    private $rules = [
        //'img' => 'required|image',
    ];

    public function validate() {
        $this->prepareForValidation();
        parent::validate();
    }


    protected function prepareForValidation() {
        if($this->route('photos')) {
            $this->rules['img'] = 'image';
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
        return $this->rules;
    }
}
