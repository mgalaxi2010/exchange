<?php

namespace App\Repositories\Eloquent;

use App\Models\Coin;
use App\Repositories\CoinRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CoinRepository extends BaseRepository implements CoinRepositoryInterface
{

    protected Coin $coin;

    function getModel(): Model
    {
        return new Coin();
    }

    public function coins()
    {
        return $this->getModel()->all();
    }

    public function getCoinBySymbol(string $coin)
    {
        return $this->getModel()::where('symbol',strtoupper($coin))->first();
    }


}
