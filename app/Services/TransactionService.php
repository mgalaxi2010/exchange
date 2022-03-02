<?php

namespace App\Services;


use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\TransactionRepositoryInterface;

class TransactionService
{


    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {

        $this->transactionRepository = $transactionRepository;
    }

    public function userTransaction()
    {
        return $this->transactionRepository->userTransactions();
    }
}
