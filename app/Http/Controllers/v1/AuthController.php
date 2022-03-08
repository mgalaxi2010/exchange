<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Response;
use function response;

class AuthController extends Controller
{

    protected AuthService $authService;
    private UserService $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * @throws \Exception
     */
    public function register(UserRequest $request)
    {
        $data = ['email' => $request['email'], 'password' => $request['password']];
        try {
            $user = $this->userService->create($data);
            if($user) {
                $result = [
                    "status" => Response::HTTP_OK,
                    "message" => "user registered successfully"];
            }else{
                $result = [
                    "status"=>Response::HTTP_INTERNAL_SERVER_ERROR,
                    "error"=>"something went wrong try again later."
                ];
            }
        }catch (Exception $e){
            $result = [
                "status"=>Response::HTTP_INTERNAL_SERVER_ERROR,
                "error"=>$e->getMessage()
            ];
        }
        return response($result);
    }

    public function login(Request $request)
    {
        try {
            $data = ['email' => $request['email'], 'password' => $request['password']];
            $authenticated = $this->authService->AuthenticateUser($data);
            if (!$authenticated) {
                return response([
                    'message' => "Invalid email or password"
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $this->authService->generateToken();
            return response([
                'message' => 'successful login',
                'access_token' => $token
            ], Response::HTTP_OK);
        }catch (\Exception $e){
            return response([
                'error' => $e->getMessage(),
                'access_token' => '',
            ], Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logout()
    {
        try {
            $this->authService->deleteToken();
            return response([
                'message' => 'successful logout',
                'access_token' => ''
            ],Response::HTTP_OK);
        }catch (\Exception $exception){
            return response([
                'error' => $exception->getMessage(),
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
