<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::post('register', [App\Http\Controllers\API\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login']);
    // logout
    Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    // end point check user
    Route::get('user', [App\Http\Controllers\API\AuthController::class, 'fetch']);

});
