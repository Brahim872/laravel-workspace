<?php

use App\Http\Controllers\Auth\FacebookAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Payments\PaymentAddAppsBuildingStripeController;
use App\Http\Controllers\Payments\PaymentStripeController;
use App\Http\Controllers\Payments\WebhookStripeController;
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
    return '<a href="/auth/google">google</a> ' . '<a href="/auth/facebook">facebook</a> ' . ' | <a href="/documentation">documentation</a>';
});
Route::get('documentation', function () {
    return view('documentation');
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('auth/facebook/callback', [FacebookAuthController::class, 'handleFacebookCallback']);
Route::get('auth/facebook', [FacebookAuthController::class, 'redirectToFacebook'])->name('auth.facebook');

Route::get('/checkout/success', [PaymentStripeController::class, 'success'])
    ->name('checkout.success');

Route::get('/checkout/cancel', [PaymentStripeController::class, 'cancel'])
    ->name('checkout.cancel');

Route::get('/webhook/endpoint', [WebhookStripeController::class, 'webhook'])
    ->name('checkout.webhook.endpoint');

//require __DIR__.'/auth.php';

