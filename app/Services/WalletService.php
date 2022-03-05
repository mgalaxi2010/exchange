<?php

namespace App\Services;


use App\Repositories\CoinRepositoryInterface;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Support\Facades\Auth;
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

    public function updateWallet($amount, $coin, $type): array
    {

        try {
            DB::beginTransaction();

            $lastBalance = $this->userRepository->userCoinBalance($coin);
            $lastBalanceAmount = $lastBalance ? floatval($lastBalance['pivot']['amount']) : 0;

            // update user-coin
            $user = Auth::user();
            $RialCoin = $this->coinRepository->getCoinBySymbol($coin);

            if ($lastBalance) {
                $newAmount = ($type == 'deposit') ? (floatval($lastBalance['pivot']['amount']) + $amount) : (floatval($lastBalance['pivot']['amount']) - floatval($amount));
                $user->coins()->lockForUpdate()->wherePivot('coin_id', $lastBalance['pivot']['coin_id'])->updateExistingPivot($lastBalance['pivot']['coin_id'], ['users_coins.amount' => $newAmount], false);
            } else {
                $user->coins()->lockForUpdate()->attach([$RialCoin['id'] => compact("amount")]);
            }
            $result = [
                'status' => Response::HTTP_OK,
                'result' => "wallet updated successfully"];

            // add transaction
            $transactionType = $this->transactionRepository->getTransactionType('deposit');
            $transactionData = [
                'user_id' => Auth::id(),
                'transaction_type_id' => $transactionType['id'],
                'coin_id_from' => $RialCoin['id'],
                'price_from' => floatval($RialCoin['price']),
                'amount_from' => $lastBalanceAmount,
                'coin_id_to' => $RialCoin['id'],
                'price_to' => floatval($RialCoin['price']),
                'amount_to' => floatval($amount) + $lastBalanceAmount
            ];
            $this->transactionRepository->create($transactionData);

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
