<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


Route::middleware('guest')->group(function () {

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware([/*'check.token',*/'verified'])
        ->name('login');


    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');


    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');


    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');


});

//account
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('/workspace', [WorkspaceController::class, 'store'])
        ->name('workspace');

    Route::get('/workspaces', [WorkspaceController::class, 'index'])
        ->middleware('hasWorkspace')
        ->name('workspace');

});

//workspace
Route::middleware(['auth:sanctum','hasWorkspace'])->prefix('{workspace}')->group(function () {

    Route::post('invitation', [InviteController::class,'store'])
        ->name('storeInvitation');

});
