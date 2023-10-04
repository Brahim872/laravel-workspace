<?php

use App\Http\Controllers\Payments\PaymentStripeController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;


//workspace

Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {


    Route::post('/checkout/{plan}', [PaymentStripeController::class, 'checkout'])
        ->prefix('workspace/{id}');

    Route::post('/workspace', [WorkspaceController::class, 'store'])
        ->name('workspace.store');

    Route::get('/workspaces', [WorkspaceController::class, 'index'])
        ->middleware('hasWorkspace')
        ->name('workspace.index');

    Route::post('switch-workspace', [WorkspaceController::class,'change'])
        ->middleware('hasWorkspace')
        ->name('workspace.change');

});

