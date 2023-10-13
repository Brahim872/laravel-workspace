<?php


use App\Http\Controllers\App\AppBoardController;
use App\Http\Controllers\App\AppBuildingController;

Route::prefix('/board')->group(function () {


    Route::post('create', [AppBoardController::class, 'store'])
        ->name('create.board');


//    Route::post('/create-app', [AppBuildingController::class, 'store'])
//        ->middleware(['checkPlan:plan_one','EnsureHaveAppsToBuilding'])
//        ->name('create.app.store');

});
