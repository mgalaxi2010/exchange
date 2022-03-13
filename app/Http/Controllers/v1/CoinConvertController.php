<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertCoinRequest;
use App\Services\CoinConvertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoinConvertController extends Controller
{


    protected CoinConvertService $coinConvertService;

    public function __construct(CoinConvertService $coinConvertService)
    {

        $this->coinConvertService = $coinConvertService;
    }

    public function convertOrder(ConvertCoinRequest $request)
    {
        $result = $this->coinConvertService->convertOrder($request);
        return response()->json($result);
    }

    public function orders()
    {
        $result = $this->coinConvertService->orders();
        return response()->json($result);
    }
}
