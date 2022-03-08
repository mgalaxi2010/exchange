<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoinApiResource;
use App\Services\CoinService;
use Illuminate\Support\Facades\Log;
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
        try {
            $coins = $this->coinService->coins();
            if (count($coins) > 0 && isset($coins[0]["price"])) {
                $result = [
                    'status' => Response::HTTP_OK,
                    'coins' => CoinApiResource::collection($coins)
                ];
            } else {
                $result = [
                    'status' => Response::HTTP_NO_CONTENT,
                    'message' => "error retrieving coins, try again later."];
            }
        }catch (\Exception $e){
            $result = [
                'status' => Response::HTTP_NO_CONTENT,
                'error'=>$e->getMessage()
            ];
        }

        return response()->json($result);
    }


}
