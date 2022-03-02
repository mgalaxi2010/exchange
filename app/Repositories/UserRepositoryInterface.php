<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function coins();

    public function userWallet();

    public function userCoinBalance(string $coin);
}
