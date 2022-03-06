<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{

    public $timestamps = true;
    protected $fillable = ['name', 'symbol', 'price','max_broker_amount'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_coins', 'coin_id', 'user_id');
    }

}
