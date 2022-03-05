<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{


    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {

        $this->transactionService = $transactionService;
    }

    public function transactions()
    {
        return response()->json($this->transactionService->userTransaction());
    }
}
