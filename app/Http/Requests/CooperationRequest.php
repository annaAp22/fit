<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $exception = new ValidationException($validator, $this->response(
            [
                'action' => 'openModal',
                'modal' => view('modals.letter_fail')->render(),
            ]
        ));
        $exception->response->setStatusCode(200);
        throw $exception;
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
