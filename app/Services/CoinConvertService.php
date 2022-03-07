<?php

namespace App\Services;

use App\Models\Transaction;
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
        $brokerUser = $this->userRepository->getBrokerUser();
        $brokerBalance = $this->userRepository->userCoinBalance($brokerUser['id'],$request['convert_to']);

        $getConvertTo = $this->coinRepository->getCoinBySymbol($request['convert_to']);
        $getConvertFrom = $this->coinRepository->getCoinBySymbol($request['convert_from']);
        $userBalance = floatval($userCoinBalance['pivot']['amount']) * floatval($userCoinBalance['price']);


        if ( $userBalance >= floatval($getConvertTo['price']) * floatval($getConvertTo['pricw'])  &&
            floatval($getConvertFrom['price']) * floatval($request['amount_from']) == floatval($getConvertTo['price']) * floatval($request['amount_to'])) {
            try {
                DB::beginTransaction();

                // withdrawal user coin
                $transactionData1 = [
                    'user_id' => $userCoinBalance['pivot']['user_id'],
                    'type' => Transaction::WITHDRAWAL,
                    'coin_id' => $userCoinBalance['pivot']['coin_id'],
                    'price' => $userCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];
                $this->transactionRepository->create($transactionData1);
                $data1 = [
                    'user_id'=>$userCoinBalance['pivot']['user_id'],
                    'coin_id'=>$getConvertFrom['id'],
                    'type'=> Transaction::WITHDRAWAL,
                    'amount'=> $request['amount_from'],
                    'last_balance'=>$userCoinBalance['price'],
                    'isNew'=>(bool)$userCoinBalance
                ];
                $this->userRepository->updateUserWallet($data1);

                // deposit broker coin
                $transactionData2 = [
                    'user_id' => $brokerUser['id'],
                    'type' => Transaction::DEPOSIT,
                    'coin_id' => $userCoinBalance['pivot']['coin_id'],
                    'price' => $userCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];
                $this->transactionRepository->create($transactionData2);
                $data2 = [
                    'user_id'=>$brokerUser['id'],
                    'coin_id'=>$getConvertFrom['id'],
                    'type'=> Transaction::DEPOSIT,
                    'amount'=> $request['amount_from'],
                    'last_balance'=>$userCoinBalance['price'],
                    'isNew'=>(bool)$brokerBalance
                ];
                $this->userRepository->updateUserWallet($data2);

                /// deposit user coin
                $transactionData3 = [
                    'user_id' => $userCoinBalance['pivot']['user_id'],
                    'type' => Transaction::DEPOSIT,
                    'coin_id' => $getConvertTo['id'],
                    'price' => $getConvertTo['price'],
                    'amount' => $request['amount_to'],
                ];
                $this->transactionRepository->create($transactionData3);
                $data3 = [
                    'user_id'=>$userCoinBalance['pivot']['user_id'],
                    'coin_id'=>$getConvertFrom['id'],
                    'type'=> Transaction::DEPOSIT,
                    'amount'=> $request['amount_from'],
                    'last_balance'=>$userCoinBalance['price'],
                    'isNew'=>(bool)$userCoinBalance
                ];
                $this->userRepository->updateUserWallet($data3);


                // withdraw broker
                $transactionData4 = [
                    'user_id' => $brokerUser['id'],
                    'type' => Transaction::WITHDRAWAL,
                    'coin_id' => $getConvertTo['id'],
                    'price' => $getConvertTo['price'],
                    'amount' => $request['amount_to'],
                ];
                $this->transactionRepository->create($transactionData4);
                $data4 = [
                    'user_id'=>$brokerUser['id'],
                    'coin_id'=>$getConvertTo['id'],
                    'type'=> Transaction::WITHDRAWAL,
                    'amount'=> $request['amount_to'],
                    'last_balance'=>$brokerBalance['price'],
                    'isNew'=>(bool)$brokerBalance
                ];
                $this->userRepository->updateUserWallet($data4);


                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
            return true;
        }
        return false;
    }

}
