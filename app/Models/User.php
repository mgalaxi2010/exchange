<?php

namespace App\Models;

use Hashids\Hashids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens,Notifiable;
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

        return self::create([
            'email'=>$request['email'],
            'password'=>Hash::make($request['password']),
            'slug'=>Str::random(10)
        ]);
    }

    public function coins()
    {
        return $this->belongsToMany(Coin::class,'users_coins','user_id','coin_id');
    }

    public static function findBySlug($slug)
    {
        return self::where('slug',$slug)->first();
    }
}
