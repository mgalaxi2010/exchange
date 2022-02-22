<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function response;

class AuthController extends Controller
{

    public static $rules = [
        'email' => 'required|unique:users,email'
    ];

    public static function isValid($data)
    {
        $validation = Validator::make($data, static::$rules);

        if ($validation->passes()) {
            return true;
        }

        return false;
    }

    public function register(Request $request)
    {
        if (!$this->isValid(["email" => $request['email']])) {
            return response(["message" => "email already registered"]);
        }
        return (new User())->createUser($request);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => "Invalid email or password"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24); //1 day

        return response([
            'message' => 'successful login'
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        $cookie = cookie::forget('jwt');
        return response([
            'message' => 'successful logout'
        ])->withCookie($cookie);
    }
}
