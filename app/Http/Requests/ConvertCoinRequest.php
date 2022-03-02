<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertCoinRequest extends FormRequest
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
            'convert_from'=>'bail|required|string',
            'amount_from'=>'bail|required|numeric|min:0.00000001|max:50000000',
            'convert_to'=>'bail|required|string',
            'amount_to'=>'bail|required|numeric|min:0.00000001|max:50000000',
        ];
    }

    public function messages()
    {
        return [
            'convert_from.required'=>'this field is required',
            'convert_from.string'=>'enter the coin symbol (ex BTC)',
            'amount_from.required'=>'this field is required',
            'amount_from.numeric'=>'enter a valid number',
            'amount_from.min'=>'enter minimum amount of 0.00000001',
            'amount_from.max'=>'enter maximum amount of 50000000',
            'convert_to.required'=>'this field is required',
            'convert_to.string'=>'enter the coin symbol (ex ETH)',
            'amount_to.required'=>'this field is required',
            'amount_to.numeric'=>'enter a valid number',
            'amount_to.min'=>'enter minimum amount of 0.00000001',
            'amount_to.max'=>'enter maximum amount of 50000000'
        ];
    }
}
