<?php

namespace App\Repositories\Eloquent;

use App\Models\Coin;

use App\Models\User;
use App\Repositories\CoinConvertRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


/**
 * @property Coin $model
 */
class CoinConvertRepository extends BaseRepository implements CoinConvertRepositoryInterface
{

    public function __construct(Coin $model)
    {
        parent::__construct($model);
    }


    public function getUserCoinBalance(string $coin)
    {
        return (new UserRepository(new User()))->userCoinBalance($coin);
    }

    public function getCoinBySymbol(string $coin)
    {
        return $this->model->getCoinBySymbol($coin);
    }
}
