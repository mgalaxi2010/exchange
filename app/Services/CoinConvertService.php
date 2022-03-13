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

    public function __construct(UserRepositoryInterface        $userRepository,
                                TransactionRepositoryInterface $transactionRepository)
    {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function convertOrder($request): array
    {
        try {

            $brokerUser = $this->userRepository->getBrokerUser();

            $brokerConvertFromCoinBalance = $this->userRepository->userCoinBalance($brokerUser['id'], $request['convert_from']);
            $brokerConvertToCoinBalance = $this->userRepository->userCoinBalance($brokerUser['id'], $request['convert_to']);

            $userConvertToCoinBalance = $this->userCoinBalance(Auth::id(), $request['convert_to']);
            $userConvertFromCoinBalance = $this->userCoinBalance(Auth::id(), $request['convert_from']);

            $brokerConvertToBalance = $brokerConvertToCoinBalance ? $brokerConvertToCoinBalance['pivot']['amount'] : 0;
            $userConvertFromBalance = $userConvertFromCoinBalance ? $userConvertFromCoinBalance['pivot']['amount'] : 0;


            $userTotalRequestedAmount = $userConvertFromCoinBalance ? bcmul($userConvertFromCoinBalance['pivot']['amount'] * $userConvertFromCoinBalance['price'], 10) : 0;
            $brokerTotalRequestedAmount = $brokerConvertToCoinBalance ? bcmul($brokerConvertToCoinBalance['price'] * $request['amount_to'], 10) : 0;

            // check if broker has enough requested coin and user has enough requested amount
            if ($brokerConvertToBalance >= $request['amount_to'] && $brokerTotalRequestedAmount == $userTotalRequestedAmount && $userConvertFromBalance >= $request['amount_from']) {

                DB::beginTransaction();
                // withdrawal user convert from coin
                $transactionData1 = [
                    'user_id' => $userConvertFromCoinBalance['pivot']['user_id'],
                    'type' => Transaction::WITHDRAWAL,
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'price' => $userConvertFromCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];
                $this->transactionRepository->create($transactionData1);

                $data1 = [
                    'user_id' => $userConvertFromCoinBalance['pivot']['user_id'],
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'type' => Transaction::WITHDRAWAL,
                    'amount' => $request['amount_from'],
                    'last_balance' => $userConvertFromCoinBalance['pivot']['amount'],
                    'isNew' => (bool)$userConvertFromCoinBalance
                ];
                $this->userRepository->updateUserWallet($data1);

                // deposit broker convert from coin
                $transactionData2 = [
                    'user_id' => $brokerUser['id'],
                    'type' => Transaction::DEPOSIT,
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'price' => $userConvertFromCoinBalance['price'],
                    'amount' => $request['amount_from'],
                ];
                $this->transactionRepository->create($transactionData2);

                $data2 = [
                    'user_id' => $brokerUser['id'],
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'type' => Transaction::DEPOSIT,
                    'amount' => $request['amount_from'],
                    'last_balance' => isset($brokerConvertFromCoinBalance) ? $brokerConvertFromCoinBalance['pivot']['amount'] : 0,
                    'isNew' => (bool)$brokerConvertFromCoinBalance
                ];
                $this->userRepository->updateUserWallet($data2);

                // withdraw broker convert to coin
                $transactionData3 = [
                    'user_id' => $brokerUser['id'],
                    'type' => Transaction::WITHDRAWAL,
                    'coin_id' => $userConvertFromCoinBalance['pivot']['coin_id'],
                    'price' => $brokerConvertToCoinBalance['price'],
                    'amount' => $request['amount_to'],
                ];
                $this->transactionRepository->create($transactionData3);

                $data3 = [
                    'user_id' => $brokerUser['id'],
                    'coin_id' => $brokerConvertToCoinBalance['pivot']['coin_id'],
                    'type' => Transaction::WITHDRAWAL,
                    'amount' => $request['amount_to'],
                    'last_balance' => $brokerConvertToCoinBalance ? $brokerConvertToCoinBalance['pivot']['amount'] : 0,
                    'isNew' => (bool)$brokerConvertToCoinBalance
                ];
                $this->userRepository->updateUserWallet($data3);

                /// deposit user convert to coin
                $transactionData4 = [
                    'user_id' => $userConvertFromCoinBalance['pivot']['user_id'],
                    'type' => Transaction::DEPOSIT,
                    'coin_id' => $brokerConvertToCoinBalance['pivot']['coin_id'],
                    'price' => $brokerConvertToCoinBalance['price'],
                    'amount' => $request['amount_to'],
                ];

                $this->transactionRepository->create($transactionData4);
                $data4 = [
                    'user_id' => $userConvertFromCoinBalance['pivot']['user_id'],
                    'coin_id' => $brokerConvertToCoinBalance['pivot']['coin_id'],
                    'type' => Transaction::DEPOSIT,
                    'amount' => $request['amount_to'],
                    'last_balance' => isset($userConvertToCoinBalance) ? $userConvertToCoinBalance['pivot']['amount'] : 0,
                    'isNew' => (bool)$userConvertToCoinBalance
                ];
                $this->userRepository->updateUserWallet($data4);

                $message = "coin converted successfully";
            } else {
                $message = "broker or user doesn't have enough coin to convert or price change doesn't match";
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

    public function orders()
    {
        return $this->userRepository->orders();
    }
}
