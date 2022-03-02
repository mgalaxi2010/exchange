<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\CoinConvertRepositoryInterface;
use App\Repositories\Eloquent\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CoinConvertService
{

    /**
     * @var CoinConvertRepositoryInterface
     */
    private $coinConvertRepository;

    public function __construct(CoinConvertRepositoryInterface $coinConvertRepository)
    {
        $this->coinConvertRepository = $coinConvertRepository;
    }

    public function convertCoin($request): string
    {
        $userCoinBalance = $this->userCoinBalance($request);

        if ($userCoinBalance) {
            if ($this->validateConvertibility($request, $userCoinBalance)) {
                $message = "coin converted successfully";
            } else {
                $message = "your balance and the amount for converting do not match";
            }
        } else {
            $message = "your balance isn't enough for convert";
        }

        return $message;
    }

    public function userCoinBalance($request)
    {
        return $this->coinConvertRepository->getUserCoinBalance($request['convert_from']);
    }

    public function validateConvertibility($request, $userCoinBalance): bool
    {
        $getConvertTo = $this->coinConvertRepository->getCoinBySymbol($request['convert_to']);

        $userBalance = floatval($userCoinBalance['pivot']['amount']) * floatval($userCoinBalance['price']);
        $convertToPrice = floatval($getConvertTo['price']);
        $amountTo = floatval($request['amount_to']);
        $amountFrom = floatval($request['amount_from']);

        if ($userBalance >= $convertToPrice * $amountTo) {
            try {
                DB::beginTransaction();
                // add transaction
                $transactionRepo = new TransactionRepository(new Transaction(),new TransactionType());
                $transactionType = $transactionRepo->getTransactionType('convert');

                $transactionData = [
                    'user_id' => $userCoinBalance['pivot']['user_id'],
                    'transaction_type_id' => $transactionType['id'],
                    'coin_id_from' => $userCoinBalance['pivot']['coin_id'],
                    'price_from' => $userCoinBalance['price'],
                    'amount_from' => $amountFrom,
                    'coin_id_to' => $getConvertTo['id'],
                    'price_to' => $convertToPrice,
                    'amount_to' => $amountTo,
                    'commission' => config('api.commission.convert.fee')
                ];

                $transactionRepo->create($transactionData);

                // update user-coins



                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
            return true;
        }
        return false;
    }

}
