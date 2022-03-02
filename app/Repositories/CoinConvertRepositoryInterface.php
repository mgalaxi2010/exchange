<?php

namespace App\Repositories;


interface CoinConvertRepositoryInterface
{
    public function getUserCoinBalance(string $coin);

    public function getCoinBySymbol(string $coin);
}
