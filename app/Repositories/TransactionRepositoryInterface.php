<?php

namespace App\Repositories;

interface TransactionRepositoryInterface
{
    public function userTransactions();

    public function getTransactionType(string $type);
}
