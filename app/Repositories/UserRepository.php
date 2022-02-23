<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }

    public function coins($request)
    {
        return $this->user->getUserCoins($request);
    }

}
