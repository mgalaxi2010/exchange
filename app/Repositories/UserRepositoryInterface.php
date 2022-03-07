<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function userWallet();

    public function findById(int $id);

    public function userCoinBalance(int $id, string $coin);

    public function updateUserWallet(array $data);

    public function getBrokerUser();
}
