<?php

use App\Http\Controllers\App\AppBuildingController;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\Payments\SubscriptionPaymentStripeController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;


//workspace


Route::post('/workspace', [WorkspaceController::class, 'store'])
    ->name('workspace.store');

//    Route::post('/plan', [PlanController::class, 'store']);

Route::post('/workspaces', [WorkspaceController::class, 'index'])
    ->name('workspace.index');

Route::post('switch-workspace', [WorkspaceController::class, 'change'])
    ->middleware(['hasWorkspace:current'])
    ->name('workspace.change');


Route::post('workspace/{id}/checkout/{plan}', [SubscriptionPaymentStripeController::class, 'checkout'])
    ->middleware(['hasWorkspace:current|id']);



Route::post('workspace/{id}/unsubscription/{plan}', [SubscriptionPaymentStripeController::class, 'unsubscription'])
    ->middleware(['hasWorkspace:current|id']);


Route::middleware(['workspace.paid'])->prefix('/workspace/{id}')->group(function () {

    Route::post('send-invitation', [InviteController::class, 'store'])
        ->middleware(['checkPlan:plan_one','isOwner'])
        ->name('storeInvitation');


    Route::post('modify-workspace', [WorkspaceController::class, 'update'])
        ->name('workspace.update');

});


