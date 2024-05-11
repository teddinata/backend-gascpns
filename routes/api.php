<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TryOutController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // logout
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // end point check user
    Route::get('user', [AuthController::class, 'fetch']);

    // route for tryout on sale without auth
    Route::get('tryout/on-sale', [TryOutController::class, 'onSale']);

    // route middleware auth for user access tryout
    Route::group(['middleware' => ['auth:sanctum']], function () {
        // route for user access tryout
        Route::apiResource('tryout', TryOutController::class);

        // route for start tryout
        Route::post('tryout/{packageId}/start', [TryOutController::class, 'startTryout']);

        Route::get('/tryout/{tryoutId}/navigate', [TryOutController::class, 'navigation']);

    });

});
