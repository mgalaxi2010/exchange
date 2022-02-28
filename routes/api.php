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

Route::prefix('v1')->group(function () {

    Route::post('register', [\App\Http\Controllers\v1\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\v1\AuthController::class, 'login']);
    Route::get('coins', [\App\Http\Controllers\v1\CoinController::class, 'coins']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::group(['prefix' => 'user'], function () {
            Route::post('deposit', [\App\Http\Controllers\v1\WalletController::class, 'deposit']);
            Route::get('coins', [\App\Http\Controllers\v1\UserController::class, 'coins']);
            Route::get('transactions', [\App\Http\Controllers\v1\TransactionController::class, 'transactions']);
            Route::post('coins/convert', [\App\Http\Controllers\v1\CoinConvertController::class, 'convertCoin']);
            Route::post('logout', [\App\Http\Controllers\v1\AuthController::class, 'logout']);
        });

    });
});

