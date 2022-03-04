<?php

namespace App\Models;

use App\Repositories\Eloquent\CoinConvertRepository;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpFoundation\Response;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function coins()
    {
        return $this->belongsToMany(Coin::class, 'users_coins', 'user_id', 'coin_id')->withPivot('amount');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function userWallet()
    {
        return Auth::user()->coins()->get();
    }

    public static function userCoinBalance($coin)
    {
        return Auth::user()->coins()->where('coins.symbol', strtoupper($coin))->first();
    }

    public function updateWallet($amount, $coin, $type): array
    {
        $user = Auth::user();
        $oldBalance = self::userCoinBalance($coin);
        if ($oldBalance) {
            $newAmount = ($type == 'deposit') ? (intval($oldBalance['pivot']['amount']) + $amount) : (intval($oldBalance['pivot']['amount']) - $amount);
            $user->coins()->wherePivot('coin_id', $oldBalance['pivot']['coin_id'])->updateExistingPivot($oldBalance['pivot']['coin_id'], ['users_coins.amount' => $newAmount], false);
        } else {
            $getCoin = (new CoinConvertRepository(new Coin()))->getCoinBySymbol($coin);
            $user->coins()->attach([$getCoin['id'] => compact("amount")]);
        }
        return [
            'status' => Response::HTTP_OK,
            'result' => "wallet updated successfully"];
    }
}
