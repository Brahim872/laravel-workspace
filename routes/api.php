<?php

use App\Http\Controllers\App\AppBuildingController;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\Payments\PaymentAddAppsBuildingStripeController;
use App\Http\Controllers\Payments\PaymentStripeController;
use App\Http\Controllers\Payments\WebhookStripeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    dd(auth()->guard('sanctum')->check());
});


Route::middleware(['guest', 'throttle:6,1'])->group(function () {

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(['verified'/*,'check.token'*/])
        ->name('login');


    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed'])
        ->name('verification.verify');


    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');


    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');


});


Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {


    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');


    Route::post('/plans', [PlanController::class, 'index']);

    Route::post('accept-invitation', [InviteController::class, 'accept'])
        ->name('acceptInvitation');

    Route::post('profile', [UserController::class, 'index'])
        ->name('getProfile');

    Route::post('edit-profile', [UserController::class, 'update'])
        ->name('editProfile');

    Route::post('change-avatar', [UserController::class, 'changeAvatar'])
        ->name('changeAvatar');


//    Route::get('/webhook/endpoint', [WebhookStripeController::class, 'webhook'])
//        ->name('checkout.webhook.endpoint');

//workspace
require __DIR__.'/api/workspace.php';
require __DIR__.'/api/apps.php';
require __DIR__ . '/api/board.php';

});
