<?php

namespace App\Services;

use App\Repositories\CoinRepository;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class CoinService
{

    /**
     * @var CoinRepository
     */
    protected $coinRepository;

    public function __construct(CoinRepository $coinRepository)
    {

        $this->coinRepository = $coinRepository;
    }

    public function saveCoin($data)
    {

        $validator = Validator::make($data,[
            'name' => 'required',
            'symbol' => 'required',
            'price' => 'required'
        ]);

        if($validator->fails()){
            throw new Exception($validator->errors()->first());
        }
        return $this->coinRepository->save($data);
    }

    public function getCoins()
    {
        return $this->coinRepository->Coins();
    }

}
