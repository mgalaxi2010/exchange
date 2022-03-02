<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{

    protected $fillable = ['name', 'symbol', 'price'];
    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_coins', 'coin_id', 'user_id');
    }

    public function getCoinBySymbol($coin)
    {
        return self::where('symbol',strtoupper($coin))->first();
    }
}
