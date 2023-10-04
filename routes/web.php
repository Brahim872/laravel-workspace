<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Payments\PaymentAddAppsBuildingStripeController;
use App\Http\Controllers\Payments\PaymentStripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return '<a href="/auth/google">google</a>';
});


Route::get('auth/google', [GoogleAuthController::class,'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class,'handleGoogleCallback']);

Route::get('/checkout/success', [PaymentStripeController::class, 'success'])
    ->name('checkout.success');

Route::get('/checkout/cancel', [PaymentStripeController::class, 'cancel'])
    ->name('checkout.cancel');


Route::get('/checkout-add-app/success', [PaymentAddAppsBuildingStripeController::class, 'success'])
    ->name('checkout.add.app.success');

Route::get('/checkout-add-app/cancel', [PaymentAddAppsBuildingStripeController::class, 'cancel'])
    ->name('checkout.add.app.cancel');



//require __DIR__.'/auth.php';

