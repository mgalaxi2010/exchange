<?php

namespace App\Repositories\Eloquent;

use App\Models\Coin;
use App\Repositories\CoinRepositoryInterface;

class CoinRepository extends BaseRepository implements CoinRepositoryInterface
{

    /**
     * @var Coin
     */
    protected $coin;

    public function __Construct(Coin $coin)
    {
        $this->coin = $coin;
    }


    public function coins()
    {
        return $this->coin->getALl();
    }
}
