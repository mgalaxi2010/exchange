<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface CoinConvertRepositoryInterface
{
    public function convertCoin(Request $request);
}
