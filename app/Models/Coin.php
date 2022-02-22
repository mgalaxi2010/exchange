<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{

    protected $guarded = ['id'];
    public $timestamps = true;

    public function getALl()
    {
        return self::all();
    }
}
