<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\CoinService;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;


class CoinController extends Controller
{

    protected $service;

    public function __Construct(CoinService $coinService)
    {
        $this->service = $coinService;
    }

    public function store($data)
    {
        $data = $data->only([
            'name',
            'symbol',
            'price'
        ]);
        try {
            $result = [
                'status' => Response::HTTP_OK,
                $this->service->saveCoin($data)
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
    }

    public function coins(Request $request)
    {
        try {
            $result = [
                'status' => Response::HTTP_OK,
                'result' => $this->service->getCoins(),
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
    }


}
