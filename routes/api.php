<?php

use App\Http\Controllers\AppsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\UserController;
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


Route::middleware(['guest','throttle:6,1'])->group(function () {

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

//account
Route::middleware(['auth:sanctum','throttle:100,1'])->group(function () {


    Route::get('/subscribe-to-notifications', [NotificationController::class, 'subscribe']);
    Route::post('/send-test-notification', 'NotificationController@sendTestNotification');



    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('/workspace', [WorkspaceController::class, 'store'])
        ->name('workspace.store');

    Route::get('/workspaces', [WorkspaceController::class, 'index'])
        ->middleware('hasWorkspace')
        ->name('workspace.index');

    Route::post('switch-workspace', [WorkspaceController::class,'change'])
        ->middleware('hasWorkspace')
        ->name('workspace.change');


    Route::post('accept-invitation', [InviteController::class,'accept'])
        ->name('acceptInvitation');

    Route::get('profile', [UserController::class,'index'])
        ->name('getProfile');

    Route::post('edit-profile', [UserController::class,'update'])
        ->name('editProfile');

    Route::post('change-avatar', [UserController::class,'changeAvatar'])
        ->name('changeAvatar');

});

//workspace
Route::middleware(['auth:sanctum','hasWorkspace'])->prefix('workspace/{id}')->group(function () {

    Route::post('send-invitation', [InviteController::class,'store'])
        ->middleware('role:pack_two|free')
        ->name('storeInvitation');


    Route::post('modify-workspace', [WorkspaceController::class,'update'])
        ->middleware('role:pack_two|free')
        ->name('workspace.update');


    Route::post('pack-workspace', [PackController::class,'store'])
        ->middleware('role:pack_two|free')
        ->name('pack.workspace');




    Route::post('charts-apps', [AppsController::class,'index'])
        ->middleware('role:pack_two|free')
        ->name('charts.apps');


});
