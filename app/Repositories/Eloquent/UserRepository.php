<?php

namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    function getModel(): Model
    {
        return new User();
    }

    public function userWallet()
    {
        return Auth::user()->coins()->get();
    }

    public function userCoinBalance(string $coin)
    {
        return Auth::user()->coins()->where('coins.symbol', strtoupper($coin))->first();
    }
}
