<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EWalletPaymentController;
use App\Http\Controllers\Api\QrisPaymentController;
use App\Http\Controllers\Api\TryOutController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\VirtualAccountPaymentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RetailOutletPaymentController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\SettingsController;

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
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    // if user has avatar
    if ($user->avatar) {
        $user->avatar = url('storage/' . $user->avatar);
    } else {
        $user->avatar = url('https://ui-avatars.com/api/?name=' . urlencode($user->name));
    }

    return response()->json($user);
})->middleware('auth:sanctum');


Route::get('provinces', [LocationController::class, 'getProvinces']);
Route::get('regencies/{province_id}', [LocationController::class, 'getRegencies']);
Route::get('districts/{regency_id}', [LocationController::class, 'getDistricts']);
Route::get('villages/{district_id}', [LocationController::class, 'getVillages']);

Route::post('/tryout/transactions/va-payment/callback', [VirtualAccountPaymentController::class, 'vaCallback']);
Route::post('/tryout/transactions/qris/callback', [QrisPaymentController::class, 'qrisCallback']);
Route::post('/tryout/transactions/ewallet/callback', [EWalletPaymentController::class, 'ewalletCallback']);

// payment methdods
Route::get('/banks', [PaymentController::class, 'getAvailableBanks']);
Route::get('/ewallets', [PaymentController::class, 'getAvailableEWallets']);
Route::get('/payment-instructions/{bank}', [PaymentController::class, 'getPaymentInstructions']);

Route::group(['prefix' => 'v1'], function () {

    Route::post('register', [AuthController::class, 'register']);

    // otp verify
    Route::post('otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('otp/resend', [AuthController::class, 'resendOtp']);

    Route::post('login', [AuthController::class, 'login']);
    // logout
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // end point check user
    Route::get('user', [AuthController::class, 'fetch']);

    // route for tryout on sale without auth
    Route::get('tryout/on-sale', [TryOutController::class, 'onSale']);

    // route middleware auth for user access tryout
    Route::group(['middleware' => ['auth:sanctum']], function () {
        // route for tryout free
        Route::get('/tryout/free', [TryOutController::class, 'freePackage']);

        Route::get('tryout/favorite', [TryOutController::class, 'soalFavorite']);
        // route for user access tryout
        Route::apiResource('tryout', TryOutController::class);

        // show detail package
        // Route::get('/package/{packageId}', [TryOutController::class, 'showDetail']);
        Route::get('/package/{slug}', [TryOutController::class, 'showBySlug']);

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

        // endpoint transaction detail
        Route::get('/tryout/transactions/{transactionId}', [TransactionController::class, 'show']);

        // payment
        Route::post('/tryout/transactions/va-payment', [VirtualAccountPaymentController::class, 'vaTransaction']);

        // ewallet
        Route::post('/tryout/transactions/ewallet', [EWalletPaymentController::class, 'ewalletTransaction']);

        // qris
        Route::post('/tryout/transactions/qris', [QrisPaymentController::class, 'qrisTransaction']);

        // retail outlet
        Route::post('/tryout/transactions/retail-outlet', [RetailOutletPaymentController::class, 'retailOutletTransaction']);

        // endpoint payment menggunakan saldo user
        Route::post('/tryout/transactions/saldo', [TransactionController::class, 'saldoTransaction']);

        Route::post('tryout/transactions/{id}/cancel', [TransactionController::class, 'cancel']);

        // endpoint rankings tryout user login
        Route::get('/rankings-by-package', [TryOutController::class, 'rankingsByPackage']);

        // endpoint rankings by tryout
        Route::get('/tryout/{tryoutId}/rank', [TryoutController::class, 'getRankByTryoutId']);

        // endpoint all packages
        Route::get('/packages', [TryOutController::class, 'allPackageTryout']);

        // raport
        Route::get('/raport', [TryOutController::class, 'raport']);

        // claim free package
        Route::post('/tryout/free/claim', [TryOutController::class, 'claimFreePackage']);

        // route for transaction history for user login
        Route::get('/transactions/history', [TransactionController::class, 'history']);

        // route for transaction detail history for user login
        Route::get('/transactions/history/{transactionId}', [TransactionController::class, 'historyDetail']);

        // route edit profile
        Route::post('/profile/edit', [SettingsController::class, 'updateAccountInfo']);

        // route change password
        Route::post('/profile/change-password', [SettingsController::class, 'changePassword']);


        // Route::post('/tryout/{tryoutId}/question/{questionId}/answer', [TryoutController::class, 'answerQuestion'])
        //     ->middleware('auth:sanctum')
        //     ->name('tryout.answer');



    });

});
