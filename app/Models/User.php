<?php

namespace App\Models;

use App\Repositories\Eloquent\CoinConvertRepository;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


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

    public function deposit($amount)
    {
        $user = Auth::user();
        $oldBalance = self::userCoinBalance("IRR");
        if ($oldBalance) {
            $user->coins()->wherePivot('coin_id', $oldBalance['pivot']['coin_id'])->updateExistingPivot($oldBalance['pivot']['coin_id'], ['users_coins.amount' => intval($oldBalance['pivot']['amount']) + $amount], false);
        } else {
            $defaultCurrency = (new CoinConvertRepository(new Coin()))->getCoinBySymbol("IRR");
            $user->coins()->attach([$defaultCurrency['id'] => compact("amount")]);
        }
        return "wallet updated successfully";
    }
}
