<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CooperationRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $phone = preg_replace('/[^+0-9]/', '', $this->input('phone'));
        $this->request->add(['phone' => $phone]);
        // no default action
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|size:12|regex:/^\+[\d]*$/',
            'type' => 'required|in:666',
        ];
    }
}
