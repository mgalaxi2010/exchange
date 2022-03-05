<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Transaction extends Model
{
    protected $guarded = ['id'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
