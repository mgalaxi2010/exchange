<?php

namespace App\Http\Requests;

use App\Rules\CoinConvertRangeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
        $price_from = $this->get('price_from');
        $symbol_from = strtoupper($this->get('convert_from'));
        $price_to = $this->get('price_to');
        $symbol_to = strtoupper($this->get('convert_to'));

        return [
            'convert_from'=>'bail|required|string',
            'amount_from'=>['bail','required','numeric',new CoinConvertRangeRule($symbol_from)],
//            'price_from'=>['bail','required','numeric',Rule::exists('coins','coins.price')->where(function ($query) use($price_from , $symbol_from){
//                $query->where('coins.price',$price_from)->where('coins.symbol',$symbol_from);})],
            'convert_to'=>'bail|required|string',
            'amount_to'=>['bail','required','numeric',new CoinConvertRangeRule($symbol_to)],
//            'price_to'=>['bail','required','numeric',Rule::exists('coins','coins.price')->where(function ($query) use($price_to , $symbol_to){
//                $query->where('coins.price',$price_to)->where('coins.symbol',$symbol_to);})],
        ];
    }

    public function messages()
    {
        return [
            'convert_from.required'=>'this field is required',
            'convert_from.string'=>'enter the coin symbol (ex Btc)',
            'amount_from.required'=>'this field is required',
            'amount_from.numeric'=>'enter a valid number',
//            'price_from.required'=>'this field is required',
//            'price_from.numeric'=>'enter a valid number',
            'convert_to.required'=>'this field is required',
            'convert_to.string'=>'enter the coin symbol (ex Eth)',
            'amount_to.numeric'=>'enter a valid number',
            'amount_to.required'=>'this field is required',
//            'price_to.required'=>'this field is required',
//            'price_to.numeric'=>'enter a valid number',
        ];
    }
}
