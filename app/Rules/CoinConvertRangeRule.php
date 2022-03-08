<?php

namespace App\Rules;

use App\Repositories\Eloquent\UserRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CoinConvertRangeRule implements Rule
{
    private string $coin;
    protected string $message = "";
    private UserRepository $userRepository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $coin)
    {
        $this->coin = $coin;
        $this->userRepository = new UserRepository();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $broker = $this->userRepository->getBrokerUser();
        $brokerCoin = $this->userRepository->userCoinBalance($broker['id'], strtoupper($this->coin));

        if ($brokerCoin['pivot']['amount'] < $value) {
            $this->message = "input must be less than or equel " . $brokerCoin['pivot']['amount'];
            return false;
        }
        if ($brokerCoin['min_buy_price'] > $value) {
            $this->message = "input must be more than or equel " . $brokerCoin['min_buy_price'];
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
