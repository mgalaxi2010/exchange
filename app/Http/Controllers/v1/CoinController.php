<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\CoinService;
use Symfony\Component\HttpFoundation\Response;


class CoinController extends Controller
{

    protected $service;

    public function __Construct(CoinService $coinService)
    {
        $this->service = $coinService;
    }


    public function coins()
    {
        $result = [
            'status' => Response::HTTP_OK,
            'coins' => $this->service->coins()
        ];
        return response()->json($result);
    }


}
