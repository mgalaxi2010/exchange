<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\TransactionRepositoryInterface;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{

    protected TransactionType $transactionTypeModel;

    function getModel(): Model
    {
        return new Transaction();
    }

    public function __construct(TransactionType $transactionTypeModel)
    {

        $this->transactionTypeModel = $transactionTypeModel;
    }

    public function userTransactions(): array
    {
        try {
            $transactions =  $this->getModel()->join('users', 'transactions.user_id', '=', 'users.id')
                ->join('coins', 'transactions.coin_id', '=', 'coins.id')
                ->join('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
                ->where('users.id', Auth::id())
                ->select('coins.symbol',
                    'transactions.price',
                    'transactions.amount',
                    'transaction_types.title as type',
                    'transactions.created_at'
                )->get();
            $result = [
              'status'=>\Symfony\Component\HttpFoundation\Response::HTTP_OK,
              'transactions'=>$transactions
            ];
        }catch (\Exception $e){
            $result = [
                'status'=>\Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
                'error'=>$e->getMessage()
            ];
        }
        return $result;
    }

    public function getTransactionType(string $type)
    {
         return $this->transactionTypeModel::where('title',$type)->first();
    }


}
