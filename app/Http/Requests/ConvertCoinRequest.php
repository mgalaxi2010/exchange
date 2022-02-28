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
            'amount_from'=>'bail|required|numeric|min:4|max:10',
            'convert_to'=>'bail|required|string',
            'amount_to'=>'bail|required|numeric|min:4|max:10',
        ];
    }
}
