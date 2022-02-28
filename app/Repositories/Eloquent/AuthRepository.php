<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        parent::__Construct($user);
    }

    public function AuthenticateUser(array $data): bool
    {
        return Auth::attempt(['email'=>$data['email'],'password'=>$data['password']]);
    }

    public function generateToken()
    {
        return Auth::user()->createToken('token')->plainTextToken;
    }

    public function deleteToken()
    {
        return Auth::user()->tokens()->delete();
    }
}
