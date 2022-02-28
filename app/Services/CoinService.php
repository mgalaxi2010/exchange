<?php

namespace App\Services;

use App\Repositories\CoinRepositoryInterface;
use App\Repositories\Eloquent\CoinRepository;

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


    public function coins()
    {
        return $this->coinRepository->coins();
    }

}
