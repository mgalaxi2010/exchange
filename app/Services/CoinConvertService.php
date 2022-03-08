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
    private transactionRepositoryInterface $transactionRepository;
    private coinRepositoryInterface $coinRepository;

    public function __construct(UserRepositoryInterface        $userRepository,
                                TransactionRepositoryInterface $transactionRepository,
                                CoinRepositoryInterface        $coinRepository)
    {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->coinRepository = $coinRepository;
    }

    public function convertCoin($request): array
    {
        try {

            $brokerUser = $this->userRepository->getBrokerUser();

            $brokerConvertFromCoinBalance = $this->userRepository->userCoinBalance($brokerUser['id'],$request['convert_from']);
            $brokerConvertToCoinBalance = $this->userRepository->userCoinBalance($brokerUser['id'],$request['convert_to']);

            $brokerConvertToBalance = $brokerConvertToCoinBalance?$brokerConvertToCoinBalance['pivot']['amount'] :0;

            $userConvertToCoinBalance = $this->userCoinBalance(Auth::id(), $request['convert_to']);
            $userConvertFromCoinBalance = $this->userCoinBalance(Auth::id(), $request['convert_from']);

            $sufficient_requested_user_coin_balance = isset($userConvertFromCoinBalance) &&
                $userConvertFromCoinBalance['pivot']['amount']*$userConvertFromCoinBalance['price'] >= $brokerConvertToCoinBalance['price']*$request['amount_to'];
            // check if broker has enough requested coin

            if ($brokerConvertToBalance >= $request['amount_to'] && $sufficient_requested_user_coin_balance) {

                DB::beginTransaction();
                // withdrawal user convert from coin
                $transactionData1 = [
                    'user_id' => $userConvertFromCoinBalance['pivot']['user_id'],
                    'type' => Transaction::WITHDRAWAL,
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'price' => $userConvertFromCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];
                $transaction1 = $this->transactionRepository->create($transactionData1);

                $data1 = [
                    'user_id'=>$userConvertFromCoinBalance['pivot']['user_id'],
                    'coin_id'=>$userConvertFromCoinBalance['pivot']['coin_id'],
                    'type'=> Transaction::WITHDRAWAL,
                    'amount'=> $request['amount_from'],
                    'last_balance'=>$userConvertFromCoinBalance['pivot']['amount'],
                    'isNew'=>(bool)$userConvertFromCoinBalance
                ];
                $walletUpdate1 = $this->userRepository->updateUserWallet($data1);

                // deposit broker convert from coin
                $transactionData2 = [
                    'user_id' => $brokerUser['id'],
                    'type' => Transaction::DEPOSIT,
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'price' => $userConvertFromCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];
                $transaction2 = $this->transactionRepository->create($transactionData2);

                $data2 = [
                    'user_id'=>$brokerUser['id'],
                    'coin_id'=>$userConvertFromCoinBalance['pivot']['coin_id'],
                    'type'=> Transaction::DEPOSIT,
                    'amount'=> $request['amount_from'],
                    'last_balance'=>isset($brokerConvertFromCoinBalance) ? $brokerConvertFromCoinBalance['pivot']['amount'] : 0,
                    'isNew'=>(bool)$brokerConvertFromCoinBalance
                ];
                $walletUpdate2 = $this->userRepository->updateUserWallet($data2);

                // withdraw broker convert to coin
                $transactionData3 = [
                    'user_id' => $brokerUser['id'],
                    'type' => Transaction::WITHDRAWAL,
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'price' => $brokerConvertToCoinBalance['price'],
                    'amount' => $request['amount_to'],
                ];
                $transaction3 = $this->transactionRepository->create($transactionData3);

                $data3 = [
                    'user_id'=>$brokerUser['id'],
                    'coin_id'=>$brokerConvertToCoinBalance['pivot']['coin_id'],
                    'type'=> Transaction::WITHDRAWAL,
                    'amount'=> $request['amount_to'],
                    'last_balance'=>$brokerConvertToCoinBalance?$brokerConvertToCoinBalance['pivot']['amount']:0,
                    'isNew'=>(bool)$brokerConvertToCoinBalance
                ];
                $walletUpdate3 = $this->userRepository->updateUserWallet($data3);

                /// deposit user convert to coin
                $transactionData4 = [
                    'user_id' => $userConvertFromCoinBalance['pivot']['user_id'],
                    'type' => Transaction::DEPOSIT,
                    'coin_id' => $brokerConvertToCoinBalance['pivot']['coin_id'],
                    'price' => $brokerConvertToCoinBalance['price'],
                    'amount' => $request['amount_to'],
                ];

                $transaction4 = $this->transactionRepository->create($transactionData4);
                $data4 = [
                    'user_id'=>$userConvertFromCoinBalance['pivot']['user_id'],
                    'coin_id'=>$brokerConvertToCoinBalance['pivot']['coin_id'],
                    'type'=> Transaction::DEPOSIT,
                    'amount'=> $request['amount_to'],
                    'last_balance'=>isset($userConvertToCoinBalance) ? $userConvertToCoinBalance['pivot']['amount']:0,
                    'isNew'=>(bool)$userConvertToCoinBalance
                ];
                $walletUpdate4 = $this->userRepository->updateUserWallet($data4);

                $message = "coin converted successfully";
            } else {
                $message = "broker or user doesn't have enough coin to convert";
            }
            DB::commit();
            $result = ['status' => Response::HTTP_OK, 'result' => $message];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'result' => $e->getMessage()
            ];
        }

        return $result;
    }

    public function userCoinBalance($userId, $coin)
    {
        return $this->userRepository->userCoinBalance($userId, $coin);
    }

}
