<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{


    function getModel(): Model
    {
        return new Transaction();
    }

    public function userTransactions(): array
    {
        try {
            $transactions =  $this->getModel()->join('users', 'transactions.user_id', '=', 'users.id')
                ->join('coins', 'transactions.coin_id', '=', 'coins.id')
                ->where('users.id', Auth::id())
                ->select('coins.symbol',
                    'transactions.price',
                    'transactions.amount',
                    'transactions.type',
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

}
