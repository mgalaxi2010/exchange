<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\TransactionApiResource;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;


class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    protected $model;

    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function userTransactions()
    {
        return TransactionApiResource::collection(Auth::user()->transactions());
    }
}
