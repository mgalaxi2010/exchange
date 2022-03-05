<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxCoinRule implements Rule
{
    protected string $coin;
    protected ? string $message = "" ;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $coin)
    {
        //
        $this->coin = $coin;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $maxCoin = config('api.coinConvertValidation.'.strtolower($this->coin).'.max');
       if( $maxCoin < $value){
           $this->message = "input must be less than ".$maxCoin;
           return false;
       }
       $minCoin = config('api.coinConvertValidation.'.strtolower($this->coin).'.min');
        if( $minCoin > $value){
            $this->message = "input must be more than ".$minCoin;
            return false;
        }
       return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
