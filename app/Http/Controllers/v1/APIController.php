<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class APIController extends Controller
{
    public function coins(Request $request)
    {
        $coins = new Coin();
        return response([
            'status'=> ResponseAlias::HTTP_OK,
            'coins' => $coins->getAll()
        ]);
    }
}
