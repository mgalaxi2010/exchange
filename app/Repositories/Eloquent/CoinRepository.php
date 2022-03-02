<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\CoinApiResource;
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
        parent::__construct($coin);
    }


    public function coins()
    {
        return CoinApiResource::collection($this->model->all());
    }

    public function getCoinBySymbol(string $coin)
    {
        return $this->model->getCoinBySymbol($coin);
    }
}
