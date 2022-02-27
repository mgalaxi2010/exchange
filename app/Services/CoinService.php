<?php

namespace App\Services;

use App\Repositories\CoinRepositoryInterface;
use App\Repositories\Eloquent\CoinRepository;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class CoinService
{

    /**
     * @var CoinRepository
     */
    private $coinRepository;

    public function __construct(CoinRepositoryInterface $coinRepository)
    {

        $this->coinRepository = $coinRepository;
    }



    public function getCoins()
    {
        return $this->coinRepository->Coins();
    }

}
