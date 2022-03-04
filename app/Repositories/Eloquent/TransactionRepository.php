<?php

namespace App\Repositories\Eloquent;

use App\Http\Controllers\v1\AuthController;
use App\Http\Resources\TransactionApiResource;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


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
        return  $this->model->userTransactions();
    }

    public function getTransactionType(string $type)
    {
        return $this->transactionTypeModel->getTransactionType($type);
    }
}
