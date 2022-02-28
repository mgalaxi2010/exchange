<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertCoinRequest;
use App\Services\CoinConvertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoinConvertController extends Controller
{

    /**
     * @var CoinConvertService
     */
    protected $coinConvertService;

    public function __construct(CoinConvertService $coinConvertService)
    {

        $this->coinConvertService = $coinConvertService;
    }

    public function convertCoin(ConvertCoinRequest $request)
    {
        $result = $this->coinConvertService->convertCoin($request);
        return response()->json($result);
    }
}
