<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use App\Models\Banner;

class BannerRequest extends Request
{
    private $rules = [
        'type' => 'required',
        //'img' => 'required|image',
//        'url' => 'required'
    ];

    public function validate() {
        $this->prepareForValidation();
        parent::validate();
    }


    protected function prepareForValidation() {
        $this->rules['type'] = 'required|in:'.implode(',', array_keys(Banner::$types));
        if($this->route('banners')) {
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
