<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UserRequest extends FormRequest
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
            'email' => 'bail|required|unique:users|email'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'email is required',
            'email.unique' => 'email exist try another one',
            'email.email' => 'enter valid email',
        ];
    }
}
