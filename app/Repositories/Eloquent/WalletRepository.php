<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{

    protected Model $model;
    private UserRepository $userRepository;
    private CoinRepository $coinRepository;

    public function __Construct(UserRepository $userRepository,CoinRepository $coinRepository)
    {

        $this->userRepository = $userRepository;
        $this->coinRepository = $coinRepository;
    }

    function getModel(): Model
    {
        return new User();
    }

    public function updateWallet(float $amount, string $coin, string $type)
    {
        $lastWallet = $this->userRepository->userCoinBalance($coin);

        // update user-coin
        $user = Auth::user();
        $RialCoin = $this->coinRepository->getCoinBySymbol($coin);

        if ($lastWallet) {
            $newAmount = ($type == 'deposit') ? (floatval($lastWallet['pivot']['amount']) + $amount) : (floatval($lastWallet['pivot']['amount']) - $amount);
            $user->coins()->lockForUpdate()->wherePivot('coin_id', $lastWallet['pivot']['coin_id'])->updateExistingPivot($lastWallet['pivot']['coin_id'], ['users_coins.amount' => $newAmount], false);
        } else {
            $user->coins()->lockForUpdate()->attach([$RialCoin['id'] => compact("amount")]);
        }
    }


}
