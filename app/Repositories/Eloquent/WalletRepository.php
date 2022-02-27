<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\WalletRepositoryInterface;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{

    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function deposit($request)
    {
        return $this->model->depositWallet($request);
    }

}
