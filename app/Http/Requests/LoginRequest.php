<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' =>trans('الرجاء إدخال الايميل'),
            'email.email' =>trans('الايميل غير صحيح'),
            'email.exists' =>trans('الايميل غير صحيح'),
            'password.required' =>trans('الرجاء إدخال كلمة المرور'),

        ];
    }
}
