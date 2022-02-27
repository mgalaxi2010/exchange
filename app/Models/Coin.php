<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class Coin extends Model
{

    protected $fillable = ['name', 'symbol', 'price'];
    public $timestamps = true;

    public function getALl()
    {
        try {
            $result = [
                'status' => Response::HTTP_OK,
                'coins' => self::all()
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage()
            ];
        }
        return $result;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_coins', 'coin_id', 'user_id');
    }

    public function getDefaultCurrency()
    {
        return self::where('name', 'Rial')->first();
    }

    public function getPriceAttribute($value)
    {
        return $value . " USDT";
    }
}
