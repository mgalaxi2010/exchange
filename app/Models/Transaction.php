<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Transaction extends Model
{
    protected $guarded = ['id'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userTransactions()
    {
        return self::join('users', 'transactions.user_id', '=', 'users.id')
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
                'transaction_types.title as type',
                'transactionS.commission',
            )
            ->get();
    }

}
