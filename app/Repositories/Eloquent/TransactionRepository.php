<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\TransactionRepositoryInterface;
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

    public function userTransactions()
    {
        return $this->getModel()->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('coins as from', 'transactions.coin_id_from', '=', 'from.id')
            ->join('coins as to', 'transactions.coin_id_to', '=', 'to.id')
            ->join('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
            ->where('users.id', Auth::id())
            ->select('from.symbol as coin_from',
                'transactions.price_from',
                'transactions.amount_from',
                'to.symbol as coin_to',
                'transactions.price_to',
                'transactions.amount_to',
                'transaction_types.title as type'
            )
            ->get();
    }

    public function getTransactionType(string $type)
    {
         return $this->transactionTypeModel::where('title',$type)->first();
    }


}
