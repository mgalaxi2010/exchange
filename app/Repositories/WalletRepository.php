<?php

namespace App\Repositories;

use App\Models\User;

class WalletRepository
{

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function deposit($request)
    {
        return $this->user->depositWallet($request);
    }

}
