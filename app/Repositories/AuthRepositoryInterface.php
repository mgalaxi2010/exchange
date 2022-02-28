<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
    public function AuthenticateUser(array $data);

    public function generateToken();

    public function deleteToken();
}
