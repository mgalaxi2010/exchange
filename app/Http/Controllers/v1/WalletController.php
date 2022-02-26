<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

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

    public function deposit(Request $request)
    {
        try {
            $result = [
                'status' => Response::HTTP_OK,
                'result' => $this->walletService->deposit($request)
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
