<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends FormRequest
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
            'amount' => "bail|required|numeric|digits_between:5,10"
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => '',
            'amount.numeric' => 'enter number input',
            'amount.min' => 'enter min of 10000 Rial',
            'amount.max' => 'enter max of 500000000 Rial'
        ];
    }
}
