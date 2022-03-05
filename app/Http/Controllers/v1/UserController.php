<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletApiResource;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function coins()
    {
        $result = [
            'status' => Response::HTTP_OK,
            'coins' => WalletApiResource::collection($this->userService->coins())
        ];
        return response()->json($result);
    }

}
