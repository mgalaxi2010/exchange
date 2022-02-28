<?php

namespace App\Repositories\Eloquent;

use App\Models\Coin;

use App\Repositories\CoinConvertRepositoryInterface;
use Illuminate\Http\Request;


/**
 * @property Coin $model
 */
class CoinConvertRepository extends BaseRepository implements CoinConvertRepositoryInterface
{

    public function __construct(Coin $model)
    {
        parent::__construct($model);
    }

    public function convertCoin(Request $request)
    {

    }
}
