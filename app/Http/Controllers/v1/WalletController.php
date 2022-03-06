<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;


class WalletController extends Controller
{

    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function deposit(WalletRequest $request)
    {
        $data = [
          'user_id'=>Auth::id(),
          'type'=>'deposit',
          'amount'=>$request['amount'],
          'coin'=>'IRR'
        ];
        $result = $this->walletService->updateWallet($data);
        return response()->json($result);
    }

}
