<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{

    public $timestamps = true;
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_coins', 'coin_id', 'user_id');
    }

    public function getPriceAttribute($value)
    {
        return floatval($value); // remove useless 0 of price
    }
}
