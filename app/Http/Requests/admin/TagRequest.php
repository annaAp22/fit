<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\Request;
use Slug;

class TagRequest extends Request
{
    private $fields = array('sysname' => 'ЧПУ');
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
   * Get the error messages for the defined validation rules.
   *
   * @return array
   */
//  public function messages()
//  {
//    foreach ($this->fields as $key => $val) {
//      $result[$key.'.required'] = $val;
//    }
//    return $result;
//  }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'sysname' => 'required|sysname|unique:tags,sysname,'.$this->route('tag'),
            'text' => 'required',
            'title' => 'required',
            'description' => 'required',
            'keywords' => 'required',
        ];
    }
}
