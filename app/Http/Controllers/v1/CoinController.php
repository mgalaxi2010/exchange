<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoinApiResource;
use App\Services\CoinService;
use Symfony\Component\HttpFoundation\Response;


class CoinController extends Controller
{

    protected CoinService $coinService;

    public function __Construct(CoinService $coinService)
    {
        $this->coinService = $coinService;
    }


    public function coins()
    {
        $coins = $this->coinService->coins();
        if (count($coins) == 1) {
            $result = [
                'status' => Response::HTTP_NO_CONTENT,
                'message' => "error retrieving coins, try again later."];
        } else {
            $result = [
                'status' => Response::HTTP_OK,
                'coins' => CoinApiResource::collection($coins)
            ];
        }
        return response()->json($result);
    }


}
