<?php

namespace App\Repositories;

interface CoinRepositoryInterface
{
    public function coins();

    public function defaultCurrency();
}
