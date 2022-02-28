<?php

namespace App\Services;

use App\Repositories\Eloquent\UserRepository;
use App\Repositories\UserRepositoryInterface;

class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($data)
    {
        return $this->userRepository->create($data);
    }

    public function coins()
    {
        return $this->userRepository->coins();
    }

}
