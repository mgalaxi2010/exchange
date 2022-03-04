<?php

namespace App\Repositories;

interface WalletRepositoryInterface
{

    public function updateWallet(float $amount,string $coin,string $type);
}
