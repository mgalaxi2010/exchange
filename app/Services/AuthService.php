<?php

namespace App\Services;

use App\Repositories\AuthRepositoryInterface;


class AuthService
{

    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function AuthenticateUser($data): bool
    {
        return $this->authRepository->AuthenticateUser($data);
    }

    public function deleteToken()
    {
        return $this->authRepository->deleteToken();
    }

    public function generateToken()
    {
        return $this->authRepository->generateToken();
    }

}
