<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\CoinService;



class CoinController extends Controller
{

    protected $service;

    public function __Construct(CoinService $coinService)
    {
        $this->service = $coinService;
    }


    public function coins()
    {
        $result = $this->service->getCoins();
        return response()->json($result);
    }


}
