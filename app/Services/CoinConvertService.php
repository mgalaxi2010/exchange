<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\CoinConvertRepositoryInterface;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class CoinConvertService
{

    /**
     * @var CoinConvertRepositoryInterface
     */
    private $coinConvertRepository;
    private $walletRepository;

    public function __construct(CoinConvertRepositoryInterface $coinConvertRepository, WalletRepositoryInterface $walletRepository)
    {
        $this->coinConvertRepository = $coinConvertRepository;
        $this->walletRepository = $walletRepository;
    }

    public function convertCoin($request): array
    {
        $userCoinBalance = $this->userCoinBalance($request);

        if ($userCoinBalance) {
            if ($this->validateAndConvert($request, $userCoinBalance)) {
                $message = "coin converted successfully";
            } else {
                $message = "your balance and the amount for converting do not match";
            }
        } else {
            $message = "your balance isn't enough for convert";
        }

        return ['status' => Response::HTTP_OK, 'result' => $message];
    }

    public function userCoinBalance($request)
    {
        return $this->coinConvertRepository->getUserCoinBalance($request['convert_from']);
    }

    public function validateAndConvert($request, $userCoinBalance): bool
    {
        $getConvertTo = $this->coinConvertRepository->getCoinBySymbol($request['convert_to']);

        $userBalance = floatval($userCoinBalance['pivot']['amount']) * floatval($userCoinBalance['price']);
        $convertToPrice = floatval($getConvertTo['price']);
        $amountTo = floatval($request['amount_to']);
        $amountFrom = (floatval($request['amount_from']) * floatval($userCoinBalance['price']) - floatval($getConvertTo['price']) * $amountTo) / floatval($userCoinBalance['price']);

        if ($userBalance >= $convertToPrice * $amountTo) {
            try {
                DB::beginTransaction();
                // add transaction
                $transactionRepo = new TransactionRepository(new Transaction(), new TransactionType());
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
