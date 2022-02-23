<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }


    public function coins(Request $request)
    {
        try {
            $result =[
                'status'=>Response::HTTP_OK,
                'result'=>$this->userService->getCoins($request)
            ];
        }catch (\Exception $e){
            $result = [
                'status'=> Response::HTTP_INTERNAL_SERVER_ERROR,
                'error'=> $e->getMessage()
            ];
        }
        return response()->json($result);
    }
}
