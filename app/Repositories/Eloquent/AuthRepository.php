<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }

    public function validateUserRequest($request): bool
    {
        $validate = false;
        $validation = Validator::make(["email" => $request['email']], [
            'email' => 'required|unique:users,email'
        ]);

        if ($validation->passes()) {
            $validate = true;
        }
        return $validate;
    }

    /**
     * @throws \Exception
     */
    public function createUser($request)
    {
        return $this->user->createUser($request);
    }

    public function getUser()
    {
        return Auth::user();
    }

    public function AuthenticateUser($request): bool
    {
        return Auth::attempt($request->only('email', 'password'));
    }

    public function setCookie($user)
    {
        $token = $user->createToken('token')->plainTextToken;
        return cookie('jwt', $token, 60 * 24); //1 day
    }

    public function destroyCookie()
    {
        return cookie::forget('jwt');
    }
}
