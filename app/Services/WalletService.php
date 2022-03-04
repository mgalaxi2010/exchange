<?php

namespace App\Services;


use App\Models\Coin;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\Eloquent\CoinRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Eloquent\WalletRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;
    private $userRepository;

    /**
     * @var WalletService
     */

    public function __construct(WalletRepositoryInterface $walletRepository, UserRepositoryInterface $userRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->userRepository = $userRepository;
    }

    public function updateWallet($amount,$coin,$type): array
    {

        try {
            DB::beginTransaction();

            $lastBalance = $this->userRepository->userCoinBalance($coin);
            $lastBalance = $lastBalance ? floatval($lastBalance['pivot']['amount']) : 0;
            // update user-coin
            $result = $this->walletRepository->updateWallet($amount,$coin,$type);

            // add transaction
            $coinRepo = new CoinRepository(new Coin());
            $Rial = $coinRepo->getCoinBySymbol('IRR');
            $transactionRepo = new TransactionRepository(new Transaction(), new TransactionType());
            $transactionType = $transactionRepo->getTransactionType('deposit');
            $transactionData = [
                'user_id' => Auth::id(),
                'transaction_type_id' => $transactionType['id'],
                'coin_id_from' => $Rial['id'],
                'price_from' => floatval($Rial['price']),
                'amount_from' => $lastBalance,
                'coin_id_to' => $Rial['id'],
                'price_to' => floatval($Rial['price']),
                'amount_to' => floatval($amount) + $lastBalance
            ];
            $transactionRepo->create($transactionData);

            DB::commit();
            return $result;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

    }
}
