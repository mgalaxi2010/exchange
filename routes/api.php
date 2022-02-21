<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (){

    Route::post('register',[\App\Http\Controllers\v1\AuthController::class,'register']);
    Route::post('login',[\App\Http\Controllers\v1\AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function (){
        Route::get('user',[\App\Http\Controllers\v1\AuthController::class,'user']);
        Route::post('logout',[\App\Http\Controllers\v1\AuthController::class,'logout']);

    });
});

