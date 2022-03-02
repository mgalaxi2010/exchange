<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\WalletApiResource;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function coins()
    {
        return WalletApiResource::collection($this->model->userWallet());
    }

    public function userWallet()
    {
        return $this->model->userWallet();
    }

    public function userCoinBalance(string $coin)
    {
        return $this->model->userCoinBalance($coin);
    }
}
