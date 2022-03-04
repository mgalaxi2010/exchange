<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WalletRequest;
use App\Services\WalletService;


class WalletController extends Controller
{

    /**
     * @var WalletService
     */
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function deposit(WalletRequest $request)
    {
        $result = $this->walletService->updateWallet($request['amount'],'IRR','deposit');
        return response()->json($result);
    }

}
