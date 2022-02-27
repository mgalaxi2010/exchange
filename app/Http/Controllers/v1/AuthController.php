<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function response;

class AuthController extends Controller
{

    protected $service;

    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    public function register(Request $request)
    {
        $validate = $this->service->validateUserRequest($request);
        if ($validate) {
            $this->service->createUser($request);
            $message = "user registered successfully";
        } else {
            $message = "email already registered";
        }
        return response(["message" => $message]);
    }

    public function login(Request $request)
    {
        $authenticated = $this->service->AuthenticateUser($request);
        if (!$authenticated) {
            return response([
                'message' => "Invalid email or password"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->service->getUser();
        $cookie = $this->service->setCookie($user);

        return response([
            'message' => 'successful login'
        ])->withCookie($cookie);
    }

    public function user()
    {
        return $this->service->getUser();
    }

    public function logout()
    {
        $cookie = $this->service->destroyCookie();
        return response([
            'message' => 'successful logout'
        ])->withCookie($cookie);
    }
}
