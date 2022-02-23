<?php

namespace App\Repositories;

use App\Models\Coin;

class CoinRepository
{

    /**
     * @var Coin
     */
    protected $coin;

    public function __Construct(Coin $coin)
    {

        $this->coin = $coin;
    }

    public function save($data)
    {
        $coin = new $this->coin;
        $coin->name = $data['name'];
        $coin->symbol = $data['symbol'];
        $coin->price = $data['price'];
        $coin->save();
        return $coin->fresh();
    }

    public function coins()
    {
        return $this->coin->getALl();
    }
}
