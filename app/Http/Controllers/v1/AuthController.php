<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use function response;

class AuthController extends Controller
{
    public function register(Request $request){
        return (new User())->createUser($request);
    }

    public function login(Request $request)
    {

        if(!Auth::attempt($request->only('email','password'))){
            return response([
                'message'=>"Invalid email or password"
            ],Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie  = cookie('jwt',$token,60*24); //1 day

        return response([
            'message'=>'successful login'
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout()
    {
        Log::info("here");
        $cookie = cookie::forget('jwt');
        return response([
            'message'=> 'successful logout'
        ])->withCookie($cookie);
    }
}
