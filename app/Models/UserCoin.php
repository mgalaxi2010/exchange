<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoin extends Model
{
    protected $guarded = [];
    protected $table = "users_coins";

    public function getAmountAttribute($value)
    {
        return floatval($value); // remove useless 0 of price
    }
}
