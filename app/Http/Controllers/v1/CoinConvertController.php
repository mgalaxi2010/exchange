<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\CoinConvertService;
use Illuminate\Http\Request;

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
}
