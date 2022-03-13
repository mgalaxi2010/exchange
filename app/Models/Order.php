<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";
    protected $guarded = [];
    public $timestamps = true;

    const DEPOSIT = 1;
    const CHANGE = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeAttribute($value)
    {
        return self::getType()[$value];
    }

    public static function getType()
    {
        return [
            self::DEPOSIT => 'Deposit',
            self::CHANGE => 'CHANGE'
        ];
    }
}
