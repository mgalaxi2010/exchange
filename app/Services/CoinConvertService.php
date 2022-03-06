<?php

namespace App\Services;

use App\Repositories\CoinRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class CoinConvertService
{
    private UserRepositoryInterface $userRepository;
    private WalletRepositoryInterface $walletRepository;
    private transactionRepositoryInterface $transactionRepository;
    private coinRepositoryInterface $coinRepository;

    public function __construct(UserRepositoryInterface        $userRepository,
                                WalletRepositoryInterface      $walletRepository,
                                TransactionRepositoryInterface $transactionRepository,
                                CoinRepositoryInterface        $coinRepository)
    {
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
        $this->coinRepository = $coinRepository;
    }

    public function convertCoin($request): array
    {
        try {
            $userCoinBalance = $this->userCoinBalance(Auth::id(), $request['convert_from']);
            if ($userCoinBalance) {
                if ($this->validateAndConvert($request, $userCoinBalance)) {
                    $message = "coin converted successfully";
                } else {
                    $message = "your balance and the amount for converting do not match";
                }
            } else {
                $message = "your balance isn't enough for convert";
            }
            $result = ['status' => Response::HTTP_OK, 'result' => $message];
        } catch (\Exception $e) {
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'result' => $e->getMessage()
            ];
        }

        return $result;
    }

    public function userCoinBalance($id, $coin)
    {
        return $this->userRepository->userCoinBalance($id, $coin);
    }

    public function validateAndConvert($request, $userCoinBalance): bool
    {
        $brokerId = $this->userRepository->getBrokerId();
        $getConvertTo = $this->coinRepository->getCoinBySymbol($request['convert_to']);
        $userBalance = floatval($userCoinBalance['pivot']['amount']) * floatval($userCoinBalance['price']);
        $brokerBalance = $this->userRepository->userCoinBalance($brokerId,$request['convert_to']);


        if ($userBalance >= floatval($getConvertTo['price']) * floatval($request['amount_to'])) {
            try {
                DB::beginTransaction();

                // add transaction
                $transactionType = $this->transactionRepository->getTransactionType('convert');
                $transactionData = [
                    'user_id' => $userCoinBalance['pivot']['user_id'],
                    'transaction_type_id' => $transactionType['id'],
                    'coin_id' => $userCoinBalance['pivot']['coin_id'],
                    'price' => $userCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];

                $this->transactionRepository->create($transactionData);

                // update user-coin



                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
            return true;
        }
        return false;
    }

}
