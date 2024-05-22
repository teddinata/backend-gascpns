<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EWalletPaymentController;
use App\Http\Controllers\Api\QrisPaymentController;
use App\Http\Controllers\Api\TryOutController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\VirtualAccountPaymentController;

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

Route::post('/tryout/transactions/va-payment/callback', [VirtualAccountPaymentController::class, 'vaCallback']);
Route::post('/tryout/transactions/qris/callback', [QrisPaymentController::class, 'qrisCallback']);
Route::post('/tryout/transactions/ewallet/callback', [EWalletPaymentController::class, 'ewalletCallback']);

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
        Route::get('tryout/favorite', [TryOutController::class, 'soalFavorite']);
        // route for user access tryout
        Route::apiResource('tryout', TryOutController::class);

        // route for start tryout
        Route::post('tryout/{packageId}/start', [TryOutController::class, 'startTryout']);

        Route::get('/tryout/{tryoutId}/navigate', [TryOutController::class, 'navigation']);

        Route::get('/tryout/{questionId}', [TryOutController::class, 'show']);

        // Route::get('/tryout/{tryoutId}/question/{questionNumber}', [TryoutController::class, 'show'])
        //     ->name('tryout.show');

        Route::post('/tryout/{questionId}/answer', [TryOutController::class, 'answerQuestion'])
            ->name('tryout.answer');

        // endpoint finish tryout
        Route::post('/tryout/{tryoutId}/finish', [TryOutController::class, 'finishTryout']);

        // endpoint summary
        Route::get('/tryout/{tryoutId}/summary', [TryOutController::class, 'summary']);

        // summary show
        Route::get('/tryout/summary/{questionId}', [TryOutController::class, 'showSummary']);

        // endpoint transactions
        Route::post('/tryout/transactions/store', [TransactionController::class, 'store']);

        // payment
        Route::post('/tryout/transactions/va-payment', [VirtualAccountPaymentController::class, 'vaTransaction']);

        // ewallet
        Route::post('/tryout/transactions/ewallet', [EWalletPaymentController::class, 'ewalletTransaction']);

        // qris
        Route::post('/tryout/transactions/qris', [QrisPaymentController::class, 'qrisTransaction']);


        // Route::post('/tryout/{tryoutId}/question/{questionId}/answer', [TryoutController::class, 'answerQuestion'])
        //     ->middleware('auth:sanctum')
        //     ->name('tryout.answer');



    });

});
