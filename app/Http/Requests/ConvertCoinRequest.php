<?php

namespace App\Http\Requests;

use App\Rules\CoinConvertRangeRule;
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
            'amount_from'=>['bail','required','numeric',new CoinConvertRangeRule($this->request->get('convert_from'))],
            'convert_to'=>'bail|required|string',
            'amount_to'=>['bail','required','numeric',new CoinConvertRangeRule($this->request->get('convert_to'))],
        ];
    }

    public function messages()
    {
        return [
            'convert_from.required'=>'this field is required',
            'convert_from.string'=>'enter the coin symbol (ex Btc)',
            'amount_from.required'=>'this field is required',
            'amount_from.numeric'=>'enter a valid number',
            'convert_to.required'=>'this field is required',
            'convert_to.string'=>'enter the coin symbol (ex Eth)',
            'amount_to.numeric'=>'enter a valid number',
            'amount_to.required'=>'this field is required',
        ];
    }
}
