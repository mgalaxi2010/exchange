<?php

namespace App\Services;


use App\Repositories\CoinRepositoryInterface;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class WalletService
{

    private UserRepositoryInterface $userRepository;
    private TransactionRepository $transactionRepository;
    private CoinRepositoryInterface $coinRepository;
    private WalletRepositoryInterface $walletRepository;
    /**
     * @var WalletService
     */

    public function __construct(UserRepositoryInterface $userRepository,
                                TransactionRepository   $transactionRepository,
                                CoinRepositoryInterface $coinRepository,
                                WalletRepositoryInterface $walletRepository)
    {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->coinRepository = $coinRepository;
        $this->walletRepository = $walletRepository;
    }

    public function updateWallet($data): array
    {

        try {
            DB::beginTransaction();

            $lastBalance = $this->userRepository->userCoinBalance($data['user_id'],$data['coin']);
            $lastBalanceAmount = $lastBalance ? floatval($lastBalance['pivot']['amount']) : 0;

            // update user-coin
            $user = $this->userRepository->findById($data['user_id']);
            $coin = $this->coinRepository->getCoinBySymbol($data['coin']);
            $data['last_balance']=$lastBalanceAmount;
            $data['isNew'] = (bool)$lastBalance;
            $data['coin_id'] = $coin['id'];
            $this->userRepository->updateUserWallet($data);

            // add transaction
            $transactionData = [
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'coin_id' => $coin['id'],
                'price' => floatval($coin['price']),
                'amount' => $data['amount'],
            ];
            $this->transactionRepository->create($transactionData);
            $result=[
                'status' => Response::HTTP_OK,
                'result' => "wallet updated successfully"
            ];
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage()
            ];
        }

        return $result;
    }
}
