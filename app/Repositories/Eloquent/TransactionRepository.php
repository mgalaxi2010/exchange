<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\TransactionApiResource;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;


class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    protected $model;
    protected $transactionTypeModel;

    public function __construct(Transaction $model,TransactionType $transactionTypeModel)
    {
        parent::__construct($model);
        $this->transactionTypeModel = $transactionTypeModel;
    }

    public function userTransactions()
    {
        return new TransactionApiResource(Auth::user()->transactions());
    }

    public function getTransactionType(string $type)
    {
        return $this->transactionTypeModel->getTransactionType($type);
    }
}
