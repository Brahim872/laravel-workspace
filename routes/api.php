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

    Route::post('/plans', [PlanController::class, 'index']);


    Route::post('workspace/{id}/checkout-add-apps/{plan}', [PaymentAddAppsBuildingStripeController::class, 'checkout']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::post('/workspace', [WorkspaceController::class, 'store'])
        ->name('workspace.store');

//    Route::post('/plan', [PlanController::class, 'store']);

    Route::post('/workspaces', [WorkspaceController::class, 'index'])
        ->name('workspace.index');

    Route::post('switch-workspace', [WorkspaceController::class, 'change'])
        ->middleware(['hasWorkspace:current'])
        ->name('workspace.change');

    Route::post('accept-invitation', [InviteController::class, 'accept'])
        ->name('acceptInvitation');

    Route::post('profile', [UserController::class, 'index'])
        ->name('getProfile');

    Route::post('edit-profile', [UserController::class, 'update'])
        ->name('editProfile');

    Route::post('change-avatar', [UserController::class, 'changeAvatar'])
        ->name('changeAvatar');




    Route::post('workspace/{id}/checkout/{plan}', [PaymentStripeController::class, 'checkout'])
        ->middleware(['hasWorkspace:current|id']);


//workspace
    Route::middleware(['hasWorkspace'])->prefix('/workspace/{id}')->group(function () {

        Route::post('send-invitation', [InviteController::class, 'store'])
            ->name('storeInvitation');


        Route::post('modify-workspace', [WorkspaceController::class, 'update'])
            ->name('workspace.update');


//        Route::post('plan-workspace', [PlanController::class, 'store'])
//            ->name('plan.workspace');


        Route::post('charts-apps', [AppsController::class, 'index'])
            ->name('charts.apps');


        Route::post('/create-app', [AppBuildingController::class, 'store'])
            ->middleware(['EnsureHaveAppsToBuilding', 'checkPlan:plan_one'])
            ->name('create.app.store');


    });
});
