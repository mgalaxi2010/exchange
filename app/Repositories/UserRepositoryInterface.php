<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function userWallet();

    public function userCoinBalance(string $coin);
}
