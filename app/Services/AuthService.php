<?php

namespace App\Services;

use App\Repositories\AuthRepositoryInterface;
use App\Repositories\Eloquent\AuthRepository;


class AuthService
{

    /**
     * @var AuthRepository
     */
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {

        $this->authRepository = $authRepository;
    }

    public function validateUserRequest($request): bool
    {
       return $this->authRepository->validateUserRequest($request);
    }

    /**
     * @throws \Exception
     */
    public function createUser($request)
    {
        return $this->authRepository->createUser($request);
    }

    public function AuthenticateUser($request): bool
    {
        return $this->authRepository->AuthenticateUser($request);
    }
    public function destroyCookie()
    {
        return $this->authRepository->destroyCookie();
    }

    public function setCookie($user)
    {
        return $this->authRepository->setCookie($user);
    }
    public function getUser()
    {
        return $this->authRepository->getUser();
    }
}
