<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    const DEPOSIT = 1;
    const WITHDRAWAL = 2;

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeAttribute($value)
    {
        return self::getType()[$value];
    }

    public static function getType()
    {
        return [
            self::DEPOSIT => 'Deposit,',
            self::WITHDRAWAL => 'Withdrawal,'
        ];
    }

}
