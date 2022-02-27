<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        'slug',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'id'
    ];

    public function createUser($request)
    {
        try {
            return self::create([
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'slug' => Str::random(10)
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function coins()
    {
        return $this->belongsToMany(Coin::class, 'users_coins')->withPivot('amount');
    }

    public static function findBySlug($slug)
    {
        $user = self::where('slug', $slug)->first();
        if ($user) {
            return $user;
        } else {
            throw new \Exception("User Not Found!");
        }
    }

    public function getUserCoins()
    {
        return Auth::user()->coins()->get();
    }

    public static function getUserBalance($user)
    {
        return $user->coins()->where('coins.name', 'Rial')->select('amount as amount', 'coin_id as coin_id')->first();
    }

    public function depositWallet($request)
    {
        $user = self::findBySlug($request['slug']);
        $old_rial_balance = self::getUserBalance($user);
        if ($old_rial_balance) {
            $user->coins()->wherePivot('coin_id', $old_rial_balance['coin_id'])->updateExistingPivot($old_rial_balance['coin_id'], ['users_coins.amount' => $old_rial_balance['amount'] + intval($request['amount'])], false);
        } else {
            $default_currency = (new Coin())->getDefaultCurrency();
            $user->coins()->attach([$default_currency['id'] => ["amount" => $request['amount']]]);
        }
        return "wallet updated successfully";
    }
}
