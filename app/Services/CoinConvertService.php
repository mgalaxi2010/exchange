<?php

namespace App\Services;

use App\Repositories\CoinRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WalletRepositoryInterface;
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
            $userCoinBalance = $this->userCoinBalance($request['convert_from']);
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

    public function userCoinBalance($coin)
    {
        return $this->userRepository->userCoinBalance($coin);
    }

    public function validateAndConvert($request, $userCoinBalance): bool
    {
        $getConvertTo = $this->coinRepository->getCoinBySymbol($request['convert_to']);
        $userBalance = floatval($userCoinBalance['pivot']['amount']) * floatval($userCoinBalance['price']);
        $convertToPrice = floatval($getConvertTo['price']);

        $amountTo = floatval($request['amount_to']);
        $amountFrom = floatval($request['amount_from']);

        if ($userBalance >= $convertToPrice * $amountTo) {
            try {
                DB::beginTransaction();

                // amountTo null means convert all the user balance to new coin
                if ($amountTo > 0) {
                    $amountFrom = $convertToPrice * $amountTo /  floatval($userCoinBalance['price']);
                    Log::info($amountFrom);
                } else {
                    $amountTo = $amountFrom * floatval($userCoinBalance['price'])/ $convertToPrice;
                }
                // add transaction
                $transactionType = $this->transactionRepository->getTransactionType('convert');

                $transactionData = [
                    'user_id' => $userCoinBalance['pivot']['user_id'],
                    'transaction_type_id' => $transactionType['id'],
                    'coin_id_from' => $userCoinBalance['pivot']['coin_id'],
                    'price_from' => $userCoinBalance['price'],
                    'amount_from' => $amountFrom,
                    'coin_id_to' => $getConvertTo['id'],
                    'price_to' => $convertToPrice,
                    'amount_to' => $amountTo
                ];

                $this->transactionRepository->create($transactionData);

                // update user-coin

                $this->walletRepository->updateWallet($amountFrom, $request['convert_from'], 'withdrawal');
                $this->walletRepository->updateWallet($amountTo, $request['convert_to'], 'deposit');

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
            return true;
        }
        return false;
    }

}
